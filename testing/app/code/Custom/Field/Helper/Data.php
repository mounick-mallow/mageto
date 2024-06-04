<?php

namespace Custom\Field\Helper;

use Magento\Checkout\Model\Session as CheckoutSession;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Private
     *
     * @var productTypeInstance
     */
    private $productTypeInstance;

    /**
     * Protected
     *
     * @var _registry
     */
    protected $_registry;

    /**
     * Protected
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * Protected
     *
     * @var _storeManager
     */
    protected $_storeManager;

    /**
     * Protected
     *
     * @var _customerSession
     */
    protected $_customerSession;

    /**
     * Protected
     *
     * @var scopeConfig
     */
    protected $scopeConfig;
    
    /**
     * Private
     *
     * @var resolver
     */
    private $resolver;

    /**
     * Protected
     *
     * @var orderFactory
     */
    protected $orderFactory;

    /**
     * Protected
     *
     * @var priceCurrencyFactory
     */
    protected $priceCurrencyFactory;

    /**
     * Protected
     *
     * @var productRepository
     */
    protected $productRepository;

    /**
     * Protected
     *
     * @var categoryRepository
     */
    protected $categoryRepository;

    /**
     * Protected
     *
     * @var categoryFactory
     */
    protected $categoryFactory;

    /**
     * Private
     *
     * @var checkoutSession
     */
    private $checkoutSession;

    /**
     * Protected
     *
     * @var customerRepository
     */
    protected $customerRepository;

    /**
     * Protected
     *
     * @var customerSession
     */
    protected $customerSession;

    /**
     * Constructor
     *
     * @var $context
     * @param context $context
     * @var $productTypeInstance
     * @param productTypeInstance $productTypeInstance
     * @var $storeManager
     * @param storeManager $storeManager
     * @var $registry
     * @param registry $registry
     * @var $customerSession
     * @param customerSession $customerSession
     * @var $scopeConfig
     * @param scopeConfig $scopeConfig
     * @var $productCollectionFactory
     * @param productCollectionFactory $productCollectionFactory
     * @var $countryFactory
     * @param countryFactory $countryFactory
     * @var $resolver
     * @param resolver $resolver
     * @var $orderFactory
     * @param orderFactory $orderFactory
     * @var $priceCurrencyFactory
     * @param priceCurrencyFactory $priceCurrencyFactory
     * @var $productRepository
     * @param productRepository $productRepository
     * @var $categoryFactory
     * @param categoryFactory $categoryFactory
     * @var $categoryRepository
     * @param categoryRepository $categoryRepository
     * @var $customerRepositoryFactory
     * @param customerRepositoryFactory $customerRepositoryFactory
     * @var $checkoutSession
     * @param checkoutSession $checkoutSession
     */

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $productTypeInstance,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Framework\Locale\Resolver $resolver,
        \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory,
        \Magento\Directory\Model\CurrencyFactory $priceCurrencyFactory,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \Magento\Customer\Api\CustomerRepositoryInterfaceFactory $customerRepositoryFactory,
        CheckoutSession $checkoutSession
    ) {
        $this->_registry = $registry;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->productTypeInstance = $productTypeInstance;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->resolver = $resolver;
        $this->orderFactory = $orderFactory;
        $this->priceCurrencyFactory = $priceCurrencyFactory;
        $this->productRepository = $productRepository;
        $this->categoryFactory = $categoryFactory;
        $this->categoryRepository = $categoryRepository;
        $this->checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * Return Store Manager
     *
     * @return
     */
    public function getStoreManager()
    {
        return $this->_storeManager;
    }

    /**
     * Product Type Instance Manager
     *
     * @return
     */
    public function getProductTypeInstanceManager()
    {
        return $this->productTypeInstance;
    }

    /**
     * Return currency symbol
     *
     * @return
     */
    public function getCurrencySymbol()
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }

    /**
     * Return media url
     *
     * @return
     */
    public function getMediaUrl()
    {
        $mediaUrl =  $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        return $mediaUrl;
    }

    /**
     * Return base url
     *
     * @return
     */
    public function getBaseUrl()
    {
        return $this->scopeConfig->getValue("web/secure/base_url");
    }

    /**
     * Return product collection
     *
     * @return
     */
    public function getProductCollection()
    {
        
        $collection = $this->_productCollectionFactory->create();

        return $collection;
    }

    /**
     * Return current product
     *
     * @return
     */
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    /**
     * Return product data
     *
     * @return
     */
    public function getProduct()
    {
        return $this->_registry->registry('product');
    }

    /**
     * Return current category related data
     *
     * @return
     */
    public function getCurrentCategory()
    {
        return $this->_registry->registry('current_category');
    }
    
    /**
     * Return category related data
     *
     * @return
     * @param int $id
     */
    public function getCategory($id)
    {
        return $this->categoryRepository->getById($id);
    }

    /**
     * Return category related data
     *
     * @return
     * @param int $id
     */
    public function getCategories($id)
    {
        return $this->categoryFactory->create()->load($id);
    }

    /**
     * Return product related data
     *
     * @return
     * @param int $id
     */
    public function getProductById($id)
    {
        return $this->productRepository->getById($id);
    }

    /**
     * Check customer login status
     *
     * @return
     */
    public function isCustomerLoggedIn()
    {
        return $this->_customerSession->isLoggedIn();
    }

    /**
     * Return locale
     *
     * @return
     */
    public function getLocale()
    {
        return $this->resolver->getLocale();
    }

    /**
     * Return customer session data
     *
     * @return
     */
    public function getCustomerSession()
    {
        return $this->_customerSession;
    }

    /**
     * Order load By Increment Id
     *
     * @return
     * @param orderIncrementId $orderIncrementId
     */
    public function getOrderloadByIncrementId($orderIncrementId)
    {
        $this->orderFactory->create()->loadByIncrementId($orderIncrementId);
    }

    /**
     * Return currency related data
     *
     * @return
     */
    public function getCurrencyManager()
    {
        return $this->priceCurrencyFactory;
    }

    /**
     * Return attribute option
     *
     * @return
     * @param product $product
     */
    public function getAttributeOptions($product)
    {
        $options = [];
        $productAttributeOptions = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
        foreach ($productAttributeOptions as $attributeOption) {
            $options[] = $attributeOption;
        }
        return $options;
    }

    /**
     * Return attribute value
     *
     * @return
     * @param attributeOptions $attributeOptions
     */
    public function getAttributeValue($attributeOptions)
    {
        $attributeValues = [];
        foreach ($attributeOptions['values'] as $attribute) {
            $attributeValues[] = $attribute['store_label'];
        }
        return $attributeValues;
    }

    /**
     * Return quote data
     *
     * @return
     */
    public function getQoute()
    {
        return $this->checkoutSession->getQuote();
    }
}
