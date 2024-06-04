<?php

namespace LuxuryUnlimited\AjaxWishList\Controller\Index;

use BelVG\GuestWishlist\Controller\WishlistProviderInterface as GuestWishlistProviderInterface;
use BelVG\GuestWishlist\Model\ItemFactory as GuestWishlistItemFactory;
use Exception;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\UrlInterface;
use Magento\Wishlist\Controller\WishlistProviderInterface;
use Magento\Wishlist\Model\ItemFactory as WishlistItemFactory;
use Magento\Wishlist\Model\ResourceModel\Wishlist;
use Magento\Wishlist\Model\WishlistFactory;
use Magento\Checkout\Helper\Cart as CartHelper;
use Magento\Checkout\Model\Cart as CheckoutCart;
use Magento\Wishlist\Helper\Data as WishlistHelper;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class Fromcart extends \Magento\Framework\App\Action\Action
{
    /**
     * @var WishlistProviderInterface
     */
    protected $wishlistProvider;

    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var Validator
     */
    protected $formKeyValidator;

    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var GuestWishlistProviderInterface
     */
    protected $guestWishlistProvider;

    /**
     * @var WishlistFactory
     */
    protected $_wishlistFactory;

    /**
     * @var Wishlist
     */
    protected $_wishlistResource;

    /**
     * @var GuestWishlistItemFactory
     */
    private $guestWishlistItemFactory;

    /**
     * @var WishlistItemFactory
     */
    private $wishlistItemFactory;

    /**
     * @var CheckoutCart
     */
    protected $cart;

    /**
     * @var CartHelper
     */
    protected $cartHelper;

    /**
     * @var WishlistHelper
     */
    protected $wishlistHelper;

    protected $imageHelper;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param WishlistProviderInterface $wishlistProvider
     * @param GuestWishlistProviderInterface $guestWishlistProvider
     * @param Validator $formKeyValidator
     * @param JsonFactory $resultJsonFactory
     * @param WishlistFactory $wishlistFactory
     * @param Wishlist $wishlistResource
     * @param GuestWishlistItemFactory $guestWishlistItemFactory
     * @param WishlistItemFactory $wishlistItemFactory
     * @param RedirectInterface|null $redirect
     * @param UrlInterface|null $urlBuilder
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        WishlistProviderInterface $wishlistProvider,
        GuestWishlistProviderInterface $guestWishlistProvider,
        Validator $formKeyValidator,
        JsonFactory $resultJsonFactory,
        WishlistFactory $wishlistFactory,
        Wishlist $wishlistResource,
        CheckoutCart $cart,
        CartHelper $cartHelper,
        WishlistHelper $wishlistHelper,
        GuestWishlistItemFactory $guestWishlistItemFactory,
        WishlistItemFactory $wishlistItemFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Helper\Image $imageHelper,
        RedirectInterface $redirect = null,
        UrlInterface $urlBuilder = null
    ) {
        $this->_customerSession = $customerSession;
        $this->wishlistProvider = $wishlistProvider;
        $this->guestWishlistProvider = $guestWishlistProvider;
        $this->wishlistItemFactory = $wishlistItemFactory;
        $this->formKeyValidator = $formKeyValidator;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_wishlistFactory  = $wishlistFactory;
        $this->_wishlistResource = $wishlistResource;
        $this->cart = $cart;
        $this->_storeManager = $storeManager;
        $this->cartHelper = $cartHelper;
        $this->wishlistHelper = $wishlistHelper;
        $this->imageHelper = $imageHelper;
        $this->guestWishlistItemFactory = $guestWishlistItemFactory;
        $this->redirect = $redirect ?: ObjectManager::getInstance()->get(RedirectInterface::class);
        $this->urlBuilder = $urlBuilder ?: ObjectManager::getInstance()->get(UrlInterface::class);
        parent::__construct($context);
    }

    /**
     * Remove item from wishlist
     *
     * @return ResultInterface
     * @throws NotFoundException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function execute()
    {
        $itemId = (int)$this->getRequest()->getParam('item');
        $redirect = '';
        if (!$itemId) {
            $data['type'] = 'error';
            $data['message'] =  __('Item not found.');
        } else {
            try {
                if ($this->_customerSession->isLoggedIn()) {
                    $wishlist = $this->wishlistProvider->getWishlist();
                    if (!$wishlist) {
                        throw new NotFoundException(__('Page not found.'));
                    }
                    $redirect = $this->urlBuilder->getUrl('wishlist');
                } else {
                    $wishlist = $this->guestWishlistProvider->getWishlist();
                    $redirect = $this->urlBuilder->getUrl('guestwishlist/index/redirect/');
                }
                if ($wishlist && $itemId) {
                    $item = $this->cart->getQuote()->getItemById($itemId);
                    $productId = $item->getProductId();

                    $buyRequest = $item->getBuyRequest();
                    $wishlist->addNewItem($productId, $buyRequest);
                    $this->cart->getQuote()->removeItem($itemId);
                    $this->cart->save();
                    $this->wishlistHelper->calculate();
                    $wishlist->save();

                    $data['type'] =  'success';
//                    $mediaUrl = $this ->_storeManager-> getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                    $product = $item->getProduct();
                    $url = $this->imageHelper->init($product, 'product_thumbnail_image')->getUrl();

                    $data['image'] = $url;
                    $data['message'] = $item->getProduct()->getName().__(' moved to your wishlist');
                } else {
                    $data['type'] = 'error';
                    $data['image'] = '';
                    $data['message'] = __('Product not found.');
                }
            } catch (LocalizedException $e) {
                $data['type'] =  'error';
                $data['image'] = '';
                $data['message'] = __('We can\'t move the item from cart: %1.', $e->getMessage());
            } catch (Exception $e) {
                $data['type'] =  'error';
                $data['image'] = '';
                $data['message'] = __('We can\'t move the item from cart: %1.', $e->getMessage());
            }
        }

        $data['redirect'] = $redirect;
        $result = $this->resultJsonFactory->create();
        $result->setData($data);
        return $result;
    }
}
