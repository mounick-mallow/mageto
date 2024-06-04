<?php

namespace LuxuryUnlimited\HomeProductSection\Helper;

use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ProductFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Wishlist\Model\Wishlist;
use Magento\Catalog\Model\Category;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Data extends AbstractHelper
{
    public const DEFAULT_PRODUCT_COUNT = 4;
    public const SECTION_ONE_ENABLED = 'home_page/product_section_one/enable';
    public const SECTION_ONE_PRODUCT_COUNT = 'home_page/product_section_one/product_count';
    public const SECTION_ONE_CATEGORY ='home_page/product_section_one/category';

    public const SECTION_TWO_ENABLED = 'home_page/product_section_two/enable';
    public const SECTION_TWO_PRODUCT_COUNT = 'home_page/product_section_two/product_count';
    public const SECTION_TWO_CATEGORY ='home_page/product_section_two/category';

    public const SECTION_THREE_ENABLED = 'home_page/product_section_three/enable';
    public const SECTION_THREE_PRODUCT_COUNT = 'home_page/product_section_three/product_count';
    public const SECTION_THREE_CATEGORY ='home_page/product_section_three/category';

    public const PLACE_HOLDER = 'catalog/placeholder/thumbnail_placeholder';

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * @var CategoryRepository $categoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ProductFactory $productModel
     */
    protected $productModel;

    /**
     * @var PriceHelper $priceHelper
     */
    protected $priceHelper;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var SessionManage
     */
    protected $sessionManager;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManagerInterface;

    /**
     * @var Wishlist
     */
    protected $wishList;

    /**
     * @var Image $image
     */
    protected $image;

    /**
     * *
     *
     * @param ScopeConfigInterface    $scopeConfig
     * @param StoreManagerInterface   $storeManager
     * @param CategoryRepository      $categoryRepository
     * @param ProductFactory          $productModel
     * @param PriceHelper             $priceHelper
     * @param CustomerSession         $customerSession
     * @param SessionManager          $sessionManager
     * @param SessionManagerInterface $sessionManagerInterface
     * @param Wishlist                $wishList
     * @param Image                   $image
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        CategoryRepository $categoryRepository,
        ProductFactory $productModel,
        PriceHelper $priceHelper,
        CustomerSession $customerSession,
        SessionManager $sessionManager,
        SessionManagerInterface $sessionManagerInterface,
        Wishlist $wishList,
        Image $image
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->categoryRepository = $categoryRepository;
        $this->productModel = $productModel;
        $this->priceHelper = $priceHelper;
        $this->customerSession = $customerSession;
        $this->sessionManager = $sessionManager;
        $this->sessionManagerInterface = $sessionManagerInterface;
        $this->wishList = $wishList;
        $this->image = $image;
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionOneCategory()
    {
        return $this->getConfigValue(self::SECTION_ONE_CATEGORY);
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionTwoCategory()
    {
        return $this->getConfigValue(self::SECTION_TWO_CATEGORY);
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionThreeCategory()
    {
        return $this->getConfigValue(self::SECTION_THREE_CATEGORY);
    }

    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionOne()
    {
        return $this->getConfigValue(self::SECTION_ONE_ENABLED);
    }

    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionTwo()
    {
        return $this->getConfigValue(self::SECTION_TWO_ENABLED);
    }

    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionThree()
    {
        return $this->getConfigValue(self::SECTION_THREE_ENABLED);
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionOneProductCount()
    {
        $productCount =  $this->getConfigValue(self::SECTION_ONE_PRODUCT_COUNT);
        return ($productCount) ? $productCount : self::DEFAULT_PRODUCT_COUNT;
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionTwoProductCount()
    {
        $productCount =  $this->getConfigValue(self::SECTION_TWO_PRODUCT_COUNT);
        return ($productCount) ? $productCount : self::DEFAULT_PRODUCT_COUNT;
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionThreeProductCount()
    {
        $productCount =  $this->getConfigValue(self::SECTION_THREE_PRODUCT_COUNT);
        return ($productCount) ? $productCount : self::DEFAULT_PRODUCT_COUNT;
    }

    /**
     * *
     *
     * @return string
     */
    public function getPlaceHolderImage()
    {
        $mediaUrl = $this->getMediaUrl();
        $placeHolder = $this->getConfigValue(self::PLACE_HOLDER);
        return ($placeHolder)
            ? $mediaUrl . 'catalog/product/placeholder/' . $placeHolder
            : '';
    }

    /**
     * *
     *
     * @param string $path
     *
     * @return string
     */
    public function getConfigValue($path)
    {
        $storeId = $this->getCurrentStoreId();
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * *
     *
     * @return string
     */
    public function getCurrentStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }

    /**
     * *
     *
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Retrieve add to wishlist params
     *
     * @param  \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getAddToWishlistParams($product)
    {
        return $this->wishList->getAddParams($product);
    }

    /**
     * *
     *
     * @param Product $product
     */
    public function getProductImageUrl($product)
    {
        return $this->image->init($product, 'product_base_image')->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepFrame(false)
            ->getUrl();
    }

    /**
     * Get product collection by category
     *
     * @param Category $category
     * @param string $pageSize
     *
     * @return \Magento\Framework\Data\Collection\AbstractDb $collection
     */
    public function getProductCollectionByCategories($category, $pageSize)
    {
        $collection = $category->getProductCollection();
        $collection->addAttributeToSelect('*');
        $collection->setCurPage(1);
        $collection->setPageSize($pageSize);
        return $collection;
    }

    /**
     * *
     *
     * @param string $categoryId
     *
     * @return CategoryRepository $categoryRepository
     */
    public function getCategoryById($categoryId)
    {
        return $this->categoryRepository->get(
            $categoryId,
            $this->getCurrentStoreId()
        );
    }

    /**
     * *
     *
     * @param string $productId
     *
     * @return string
     */
    public function getProductUrl($productId)
    {
        $storeId = $this->getCurrentStoreId();
        $product = $this->productModel->create()->load($productId);
        $product->setStoreId($storeId);
        $url = $product->getProductUrl();
        return $url;
    }

    /**
     * *
     *
     * @param string $price
     *
     * @return string
     */
    public function getFormattedPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }

    /**
     * *
     *
     * @param Product $product
     */
    public function getMediaGalleryImageUrl($product)
    {
        return $product->getImage();
    }

    /**
     * *
     *
     * @return boolean
     */
    public function isCustomerLoggedIn()
    {
        $customer = $this->getCustomer();
        if (isset($customer['visitor_data']['do_customer_login']) && $customer['visitor_data']['do_customer_login']) {
            return true;
        }
        return false;
    }

    /**
     * *
     *
     * @return CustomerSession
     */
    public function getCustomer()
    {
        return $this->sessionManager->getData();
    }

    /**
     * *
     */
    public function getCustomerId()
    {
        if ($this->isCustomerLoggedIn()) {
            $customer = $this->getCustomer();
            return isset($customer['visitor_data']['customer_id']) ? $customer['visitor_data']['customer_id'] : '';
        } else {
            return false;
        }
    }

    /**
     * *
     */
    public function getWishListCollectionIds()
    {
        $ids = [];
        $collection =$this->wishList->loadByCustomerId($this->getCustomerId())->getItemCollection();
        foreach ($collection as $item) {
            $ids[] =  $item->getProductId();
        }
        return $ids;
    }

    /**
     * Get Customer Data
     *
     * @return false|\Magento\Customer\Model\Customer
     */
    public function getCustomerData()
    {
        if ($this->customerSession->getCustomer()) {
            $customer = $this->customerSession->getCustomer();
            return $customer;
        }
        return false;
    }

    /**
     * Get Store Data
     *
     * @return \Magento\Store\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStoreData()
    {
        return $this->storeManager->getStore();
    }
}
