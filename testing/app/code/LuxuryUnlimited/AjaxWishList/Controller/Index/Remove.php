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

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class Remove extends \Magento\Framework\App\Action\Action
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
        GuestWishlistItemFactory $guestWishlistItemFactory,
        WishlistItemFactory $wishlistItemFactory,
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
        $productId = $this->getRequest()->getParam('product');
        $redirect = '';
        if (!$productId) {
            $data['type'] = 'error';
            $data['message'] =  __('Product not found.');
        } else {
            try {
                if ($this->_customerSession->isLoggedIn()) {
                    $wishlistId = $this->_wishlistFactory->create()->load(
                        $this->_customerSession->getCustomerId(),
                        'customer_id'
                    )->getId();
                    $item = $this->wishlistItemFactory->create()->getCollection()
                        ->addFieldToFilter('product_id', $productId)
                        ->addFieldToFilter('wishlist_id', $wishlistId)
                        ->getFirstItem();
                    $wishlist = $this->wishlistProvider->getWishlist($item->getWishlistId());
                    $redirect = $this->urlBuilder->getUrl('wishlist');
                } else {
                    $item = $this->guestWishlistItemFactory->create()->load($productId, 'product_id');
                    $wishlist = $this->guestWishlistProvider->getWishlist($item->getWishlistId());
                    $redirect = $this->urlBuilder->getUrl('guestwishlist/index/redirect/');
                }
                if ($item->getId()) {
                    $item->delete();
                    $wishlist->save();
                    $data['type'] =  'success';
                    $data['message'] = __('Product removed from wishlist');
                } else {
                    $data['type'] = 'error';
                    $data['message'] = __('Product not found.');
                }
            } catch (LocalizedException $e) {
                $data['type'] =  'error';
                $data['message'] = __('We can\'t remove the item from Wish List right now: %1.', $e->getMessage());
            } catch (Exception $e) {
                $data['type'] =  'error';
                $data['message'] = __('We can\'t remove the item from Wish List right now: %1.', $e->getMessage());
            }
        }

        $data['redirect'] = $redirect;
        $result = $this->resultJsonFactory->create();
        $result->setData(['data' => $data]);
        return $result;
    }
}
