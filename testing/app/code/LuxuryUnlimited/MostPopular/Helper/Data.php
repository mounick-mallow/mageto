<?php

namespace LuxuryUnlimited\MostPopular\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Catalog\Model\ProductCategoryList;
use Magento\Catalog\Api\CategoryLinkManagementInterface;
use Magento\Catalog\Model\Product as ProductCollection;
use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory as ConfigCollection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Wishlist\Model\Wishlist;
use Magento\Catalog\Helper\Image;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Data extends AbstractHelper
{
    public const DEFAULT_PRODUCT_COUNT = 4;
    public const DEFAULT_CRON_BATCH_COUNT = 100;
    public const IS_ENABLED_MOST_POPULAR = 'home_page/most_popular/enable';
    public const PRODUCT_DISPLAY_COUNT = 'home_page/most_popular/product_count';
    public const MOST_POPULAR_CATEGORY = 'home_page/most_popular/category';
    public const CRON_BATCH_COUNT = 'home_page/most_popular/cron_batch_count';
    public const OVER_ALL_COUNT = 'home_page/most_popular/overall_count';
    public const LAST_PROCESSED_PAGE = 'home_page/most_popular/last_processed_page';
    public const LAST_PROCESSED_DATE ='home_page/most_popular/last_processed_date';
    
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
     * @var Currency $currency
     */
    // protected $currency;
    
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
     * @var TimezoneInterface $timezoneInterface
     */
    protected $timezoneInterface;

    /**
     * @var WriterInterface $configWriter
     */
    protected $configWriter;

    /**
     * @var DateTime $dateTime
     */
    protected $dateTime;

    /**
     * *
     * @var CacheManager $cacheManager
     */
    protected $cacheManager;
 
    /**
     * @var ProductCategoryList
     */
    public $productCategory;

    /**
     * @var ProductCollection $productCollection
     */
    protected $productCollection;

    /**
     * @var CategoryLinkManagementInterface $categoryLinkManagementInterface
     */
    protected $categoryLinkManagementInterface;

    /**
     * @var ConfigCollection $configCollection
     */
    protected $configCollection;

    /**
     * @var ProductCollectionFactory $productCollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

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
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param CategoryRepository $categoryRepository
     * @param ProductFactory $productModel
     * @param PriceHelper $priceHelper
     * @param TimezoneInterface $timezoneInterface
     * @param WriterInterface $configWriter
     * @param DateTime $dateTime
     * @param CacheManager $cacheManager
     * @param ProductCategoryList $productCategory
     * @param CategoryLinkManagementInterface $categoryLinkManagementInterface
     * @param ProductCollection $productCollection
     * @param ConfigCollection $configCollection
     * @param ProductCollectionFactory $productCollectionFactory
     * @param PriceCurrencyInterface $priceCurrency
     * @param CustomerSession $customerSession
     * @param SessionManager $sessionManager
     * @param SessionManagerInterface $sessionManagerInterface
     * @param Wishlist $wishList
     * @param Image $image
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        CategoryRepository $categoryRepository,
        ProductFactory $productModel,
        PriceHelper $priceHelper,
        TimezoneInterface $timezoneInterface,
        WriterInterface $configWriter,
        DateTime $dateTime,
        CacheManager $cacheManager,
        ProductCategoryList $productCategory,
        CategoryLinkManagementInterface $categoryLinkManagementInterface,
        ProductCollection $productCollection,
        ConfigCollection $configCollection,
        ProductCollectionFactory $productCollectionFactory,
        PriceCurrencyInterface $priceCurrency,
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
        $this->timezoneInterface = $timezoneInterface;
        $this->configWriter = $configWriter;
        $this->dateTime = $dateTime;
        $this->cacheManager = $cacheManager;
        $this->productCategory = $productCategory;
        $this->categoryLinkManagementInterface = $categoryLinkManagementInterface;
        $this->productCollection = $productCollection;
        $this->configCollection = $configCollection;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->priceCurrency = $priceCurrency;
        $this->customerSession = $customerSession;
        $this->sessionManager = $sessionManager;
        $this->sessionManagerInterface = $sessionManagerInterface;
        $this->wishList = $wishList;
        $this->image = $image;
    }
    
    /**
     * *
     *
     * @return boolean
     */
    public function isEnabledMostPopular()
    {
        return $this->getConfigValue(self::IS_ENABLED_MOST_POPULAR);
    }

    /**
     * *
     *
     * @return string
     */
    public function getMostPopularCategory()
    {
        return $this->getConfigValue(self::MOST_POPULAR_CATEGORY);
    }

    /**
     * *
     *
     * @return string
     */
    public function getMostPopularProductCount()
    {
        $productCount =  $this->getConfigValue(self::PRODUCT_DISPLAY_COUNT);
        return ($productCount) ? $productCount : self::DEFAULT_PRODUCT_COUNT;
    }

    /**
     * *
     *
     * @return string
     */
    public function getMostPopularCronBatchCount()
    {
        $productCount =  $this->getConfigValue(self::CRON_BATCH_COUNT);
        return ($productCount) ? $productCount : self::DEFAULT_CRON_BATCH_COUNT;
    }
    
    /**
     * *
     *
     * @return string
     */
    public function getOverallCount()
    {
        return $this->getConfigValue(self::OVER_ALL_COUNT);
    }

    /**
     * *
     *
     * @return string
     */
    public function getLastProcessedPage()
    {
        return $this->getConfigValue(self::LAST_PROCESSED_PAGE);
    }

    /**
     * *
     *
     * @return string
     */
    public function getLastProcessedDate()
    {
        return $this->getConfigValue(self::LAST_PROCESSED_DATE);
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
            ? $mediaUrl.'catalog/product/placeholder/'.$placeHolder
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
     * *
     *
     * @param CategoryRepository $category
     * @param string $pageSize
     *
     * @return ProductCollectionFactory $collection
     */
    public function getProductCollectionByCategories($category, $pageSize = null)
    {
        $fields = '*';
        $collection = $category->getProductCollection();
        $collection->addAttributeToSelect($fields);
        $collection->setCurPage(1);
        if ($pageSize) {
            $collection->setPageSize($pageSize);
        }

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
     * @param Product $product
     */
    public function getProductImageUrl($product)
    {
        return $this->image->init($product, 'product_base_image')->constrainOnly(false)
            ->keepAspectRatio(true)
            ->keepFrame(true)
            ->getUrl();
    }

    /**
     * *
     *
     * @param string $productId
     *
     * @return ProductFactory
     */
    public function getProductById($productId)
    {
        return $this->productModel->create()->load($productId);
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
     * @return string
     */
    public function getStoreDate()
    {
        return $this->timezoneInterface->date()->format('Y-m-d');
    }

    /**
     * *
     *
     * @param string $path
     * @param string $value
     */
    public function setConfigData($path, $value)
    {
        $this->configWriter->save(
            $path,
            $value
        );
    }

    /**
     * *
     *
     * @return Date
     */
    public function getPreviousDate()
    {
        $date = $this->getStoreDate();
        $previousDate = $this->dateTime->date('Y-m-d', strtotime($date." -1 days"));
        return $previousDate;
    }

    /**
     * Get all the category id
     *
     * @param int $productId
     * @return array
     */
    public function getCategoryIds(int $productId)
    {
        $categoryIds = $this->productCategory->getCategoryIds($productId);
        $category = [];
        if ($categoryIds) {
            $category = array_unique($categoryIds);
        }
        return $category;
    }

    /**
     * *
     *
     * @param string $sku
     * @param array $categoryIds
     */
    public function assignProductToCategories($sku, $categoryIds)
    {
        $product = $this->productCollection->loadByAttribute('sku', $sku);
        $product->setCategoryIds($categoryIds);
        $product->save();
    }

    /**
     * *
     *
     * @param string $path
     */
    public function getConfigCollection($path)
    {
        return $this->configCollection->create()
        ->addFieldToFilter('path', $path);
    }

    /**
     * *
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     */
    public function getProducts()
    {
        $collection = $this->productCollectionFactory->create();
        return $collection;
    }

    /**
     * *
     *
     * @param Product $product
     *
     * @return string
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
}
