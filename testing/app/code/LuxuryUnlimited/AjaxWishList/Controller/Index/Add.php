<?php

namespace LuxuryUnlimited\AjaxWishList\Controller\Index;

use BelVG\GuestWishlist\Controller\WishlistProviderInterface as GuestWishlistProviderInterface;
use BelVG\GuestWishlist\Model\ItemFactory as GuestWishlistItemFactory;
use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
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
class Add extends \Magento\Framework\App\Action\Action
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
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

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
     * @var \Magento\Wishlist\Model\WishlistFactory
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
     * @param ProductRepositoryInterface $productRepository
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
        ProductRepositoryInterface $productRepository,
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
        $this->productRepository = $productRepository;
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
     * Adding new item
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|ResultInterface|void
     * @throws NotFoundException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function execute()
    {
        $requestParams = $this->getRequest()->getParams();
        $productId = $this->getRequest()->getParam('product');
        $remove = (int)$this->getRequest()->getParam('remove');
        $uenc = $this->getRequest()->getParam('uenc');
        $requestParams['product'] = $productId;
        $requestParams['uenc'] = $uenc;
        $redirect = '';
        if (!$productId) {
            $data['type'] =  'error';
            $data['message'] =  __('Product not found.');
            $data['show_confirm'] =  false;
            $data['product'] = $productId;
        } else {
            $product = $this->getProduct($productId);
            if (!$product->isVisibleInCatalog()) {
                $data['type'] =  'error';
                $data['message'] = __('We can\'t specify a product.');
                $data['show_confirm'] =  false;
                $data['product'] = $productId;
            } else {
                if ($this->_customerSession->isLoggedIn()) {
                    $customerId = $this->_customerSession->getId();
                    $wishlist = $this->_wishlistFactory->create()->loadByCustomerId($customerId, true);
                    $redirect = $this->urlBuilder->getUrl('wishlist');
                    $item = $this->checkWishlistItem((int)$productId, false, (int)$wishlist->getId());
                } else {
                    $wishlist = $this->guestWishlistProvider->getWishlist();
                    $redirect = $this->urlBuilder->getUrl('guestwishlist/index/redirect/');
                    $item = $this->checkWishlistItem((int)$productId, true, (int)$wishlist->getId());
                }

                if ($remove && $item->getId()) {
                    try {
                        $item->delete();
                        $wishlist->save();
                        $data['type'] =  'success';
                        $data['message'] = __('Product removed from wishlist');
                        $data['show_confirm'] =  false;
                        $data['product'] = $productId;
                    } catch (Exception $e) {
                        $data['type'] =  'error';
                        $data['message'] = __(
                            'We can\'t remove the item from Wish List right now: %1.',
                            $e->getMessage()
                        );
                        $data['show_confirm'] =  false;
                        $data['product'] = $productId;
                    }
                } elseif (!$remove && $item->getId()) {
                    $data['type'] =  'success';
                    $data['message'] = __('Product already in wishlist. Do you want to remove it?');
                    $data['show_confirm'] = true;
                    $data['product'] = $productId;
                } else {
                    try {
                        $buyRequest = new \Magento\Framework\DataObject($requestParams);
                        $result = $wishlist->addNewItem($product, $buyRequest);

                        if ($wishlist->isObjectNew()) {
                            $wishlist->save();
                        }
                        $this->_eventManager->dispatch(
                            'guestwishlist_add_product',
                            ['wishlist' => $wishlist, 'product' => $product, 'item' => $result]
                        );
                        $data['type'] = 'success';
                        $data['message'] = __('Product added to wishlist');
                        $data['show_confirm'] = false;
                        $data['product'] = $productId;
                    } catch (LocalizedException $e) {
                        $data['type'] = 'error';
                        $data['message'] = __('We can\'t add the item to Wish List right now: %1.', $e->getMessage());
                        $data['show_confirm'] = false;
                        $data['product'] = $productId;
                    } catch (Exception $e) {
                        $data['type'] = 'error';
                        $data['message'] = __('We can\'t add the item to Wish List right now: %1.', $e->getMessage());
                        $data['show_confirm'] = false;
                        $data['product'] = $productId;
                    }
                }
            }

            $data['redirect'] = $redirect;
            $result = $this->resultJsonFactory->create();
            $result->setData(['data' => $data]);
            return $result;
        }
    }

    /**
     * Check Wishlist Item
     *
     * @param int $productId
     * @param mixed $guest
     * @param int $wishlistId
     * @return \BelVG\GuestWishlist\Model\Item|\Magento\Framework\DataObject
     */
    public function checkWishlistItem($productId, $guest, $wishlistId)
    {
        if (!$guest) {
            return $this->wishlistItemFactory->create()->getCollection()
                ->addFieldToFilter('product_id', $productId)
                ->addFieldToFilter('wishlist_id', $wishlistId)
                ->getFirstItem();
        } else {
            return $this->guestWishlistItemFactory->create()->getCollection()
                ->addFieldToFilter('product_id', $productId)
                ->addFieldToFilter('wishlist_id', $wishlistId)
                ->getFirstItem();
        }
    }

    /**
     * Get Product
     *
     * @param int $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface|null
     */
    public function getProduct($productId)
    {
        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            $product = null;
        }
        return $product;
    }
}
