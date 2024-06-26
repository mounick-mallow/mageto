<?php

namespace WeltPixel\OwlCarouselSlider\Block\Slider;

use Zend_Db_Expr;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class Products extends \Magento\Catalog\Block\Product\AbstractProduct implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \WeltPixel\OwlCarouselSlider\Helper\Products
     */
    protected $_helperProducts;

    /**
     * @var \WeltPixel\OwlCarouselSlider\Helper\Custom
     */
    protected $_helperCustom;

    /**
     * @var ProductType
     */
    protected $_productType;

    /**
     * @var SliderConfiguration
     */
    protected $_sliderConfiguration;

    /**
     * @var CurrentProduct
     */
    protected $_currentProduct;
    /**
     * Products visibility
     * @var \Magento\Reports\Model\Event\TypeFactory
     */
    protected $_catalogProductVisibility;

    /**
     * @var ProductCollectionFactory
     */
    protected $_productCollectionFactory;

    /**
     * @var ReportsCollectionFactory
     */
    protected $_reportsCollectionFactory;

    /**
     * @var ViewProductsBlock
     */
    protected $_viewProductsBlock;

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $_localeDate;
    /**
     * @var \WeltPixel\MobileDetect\Helper\Data
     */
    private $_mobileHelperData;

    /**
     * Products constructor.
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \WeltPixel\OwlCarouselSlider\Helper\Products $helperProducts
     * @param \WeltPixel\OwlCarouselSlider\Helper\Custom $helperCustom
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsCollectionFactory
     * @param \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportsCollectionFactory
     * @param \Magento\Reports\Block\Product\Widget\Viewed\Proxy $viewedProductsBlock
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \WeltPixel\MobileDetect\Helper\Data $mobileHelperData
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \WeltPixel\OwlCarouselSlider\Helper\Products $helperProducts,
        \WeltPixel\OwlCarouselSlider\Helper\Custom $helperCustom,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productsCollectionFactory,
        \Magento\Reports\Model\ResourceModel\Product\CollectionFactory $reportsCollectionFactory,
        //@phpstan-ignore-next-line
        \Magento\Reports\Block\Product\Widget\Viewed\Proxy $viewedProductsBlock, //@codingStandardsIgnoreLine
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \WeltPixel\MobileDetect\Helper\Data $mobileHelperData,
        array $data = []
    ) {
        $this->_coreRegistry = $context->getRegistry();
        $this->_helperCustom = $helperCustom;
        $this->_helperProducts = $helperProducts;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_productCollectionFactory = $productsCollectionFactory;
        $this->_reportsCollectionFactory = $reportsCollectionFactory;
        $this->_viewProductsBlock = $viewedProductsBlock;
        $this->_categoryFactory = $categoryFactory;
        $this->_mobileHelperData = $mobileHelperData;

        $this->setTemplate('sliders/products.phtml');

        if (($this->_currentProduct == null)) {
            $this->_currentProduct = $this->_coreRegistry->registry('current_product');
        }

        $this->_localeDate = $context->getLocaleDate();
        $this->_scopeConfig = $context->getScopeConfig();

        parent::__construct($context, $data);
    }

    /**
     * *
     */
    protected function _construct()
    {
        parent::_construct();

        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => [\Magento\Catalog\Model\Product::CACHE_TAG,
            ],]);
    }

    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            'WELTPIXEL_PRODUCTS_LIST_WIDGET',
            $this->_storeManager->getStore()->getId(),
            $this->_storeManager->getStore()->getCurrentCurrency()->getCode(),
            $this->_design->getDesignTheme()->getId(),
            $this->getData('products_type'),
            json_encode($this->getRequest()->getParams())
        ];
    }

    /**
     * Retrieve the product collection based on product type.
     *
     * @return array|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection()
    {
        $productsType = $this->getData('products_type');

        switch ($productsType) {
            case 'new_products':
                $productCollection = $this->_getNewProductCollection($this->_productCollectionFactory->create());
                break;
            case 'bestsell_products':
                $productCollection = $this->_getBestsellProductCollection($this->_productCollectionFactory->create());
                break;
            case 'sell_products':
                $productCollection = $this->_getSellProductCollection($this->_productCollectionFactory->create());
                break;
            case 'recently_viewed':
                $productCollection = $this->_getRecentlyViewedCollection($this->_productCollectionFactory->create());
                break;
            case 'category_products':
                $categoryId = $this->_getCategoryIdFrom($this->getData('category'));
                $productCollection = $this->_getCustomCategoryCollection($categoryId);
                break;
            case 'related_products':
                $productCollection = $this->getProductCollectionRelated();
                break;
            case 'upsell_products':
                $productCollection = $this->getProductCollectionUpSell();
                break;
            case 'crosssell_products':
                $productCollection = $this->getProductCollectionCrossSell();
                break;
            default:
                $productCollection = [];
        }

        return $productCollection;
    }

    /**
     * Retrieve the Slider settings.
     *
     * @return array
     */
    public function getSliderConfiguration()
    {
        $productsType = $this->getData('products_type');

        if (($this->_sliderConfiguration == null) && $this->_productType != $productsType) {
            $this->_productType = $productsType;
            $this->_sliderConfiguration = $this->_helperProducts->getSliderConfigOptions($productsType);
        }

        return $this->_sliderConfiguration;
    }

    /**
     * Retrieve the Slider Breakpoint settings.
     *
     * @return array
     */
    public function getBreakpointConfiguration()
    {
        return $this->_helperCustom->getBreakpointConfiguration();
    }

    /**
     * Get new slider products.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $_collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getNewProductCollection($_collection)
    {
        $limit = $this->_getProductLimit('new_products');
        $random = $this->_getRandomSort('new_products');

        if (!$limit || $limit == 0) {
            return [];
        }

        $_collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        if ($random) {
            $allIds = $_collection->getAllIds();
            $randomIds = [];
            $maxKey = count($allIds) - 1;
            while (count($randomIds) <= count($allIds) - 1) {
                //@codingStandardsIgnoreLine
                $randomKey = mt_rand(0, $maxKey);
                $randomIds[$randomKey] = $allIds[$randomKey];
            }

            $_collection->addIdFilter($randomIds);
        }

        $_collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        $_collection = $this->_addProductAttributesAndPrices($_collection)
            ->addAttributeToFilter(
                'news_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()],
                'left'
            )
            ->addAttributeToFilter(
                'news_to_date',
                [
                    'or' => [
                        0 => ['date' => true, 'from' => $this->getStartOfDayDate()],
                        1 => ['is' => new Zend_Db_Expr('null')],
                    ]
                ],
                'left'
            )
            ->addAttributeToSort(
                'news_from_date',
                'desc'
            )
            ->addStoreFilter($this->getStoreId())->setCurPage(1);

        if ($limit && $limit > 0) {
            $_collection->setPageSize($limit);
        }

        return $_collection;
    }

    /**
     * Get best-sell slider products.
     *
     * @param  Collection $_collection
     *
     * @return array|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getBestsellProductCollection($_collection)
    {
        $limit = $this->_getProductLimit('bestsell_products');
        $random = $this->_getRandomSort('bestsell_products');

        if (!$limit || $limit == 0) {
            return [];
        }

        $_collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        if ($random) {
            $allIds = $_collection->getAllIds();
            $candidateIds = $_collection->getAllIds();
            $randomIds = [];
            $maxKey = count($candidateIds) - 1;
            while (count($randomIds) <= count($allIds) - 1) {
                //@codingStandardsIgnoreLine
                $randomKey = mt_rand(0, $maxKey);
                $randomIds[$randomKey] = $candidateIds[$randomKey];
            }

            $_collection->addIdFilter($randomIds);
        }

        /** Prepare filter by period */
        $storeId = $this->getStoreId();
        $currentDate = $this->_localeDate->date();
        $period = $this->_sliderConfiguration['period'];
        switch ($period) {
            case 'last_day':
                $yesterday = $this->_localeDate->date(strtotime(
                    '-1 day',
                    $currentDate->getTimestamp()
                ))->format('Y-m-d');
                $periodFilter['from'] = $yesterday;
                $periodFilter['to'] = $yesterday;
                break;
            case 'last_week':
                $daysArr = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                $firstDay = $this->_scopeConfig->getValue(
                    'general/locale/firstday',
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                    $storeId
                );

                $previousWeek = strtotime('-1 week +1 day', $currentDate->getTimestamp());
                $startWeek = strtotime('last ' . $daysArr[$firstDay] . ' midnight +1 day', $previousWeek);
                $endWeek = strtotime('+6 day', $startWeek);

                $periodFilter['from'] = $this->_localeDate->date($startWeek)->format('Y-m-d');
                $periodFilter['to'] = $this->_localeDate->date($endWeek)->format('Y-m-d');
                break;
            case 'last_month':
                $firstDay = strtotime('first day of previous month', $currentDate->getTimestamp());
                $lastDay = strtotime('last day of previous month', $currentDate->getTimestamp());

                $periodFilter['from'] = $this->_localeDate->date($firstDay)->format('Y-m-d');
                $periodFilter['to'] = $this->_localeDate->date($lastDay)->format('Y-m-d');
                break;
            case 'last_year':
                $firstDay = strtotime('first day of previous year', $currentDate->getTimestamp());
                $lastDay = strtotime('last day of previous year', $currentDate->getTimestamp());

                $periodFilter['from'] = $this->_localeDate->date($firstDay)->format('Y-m-d');
                $periodFilter['to'] = $this->_localeDate->date($lastDay)->format('Y-m-d');
                break;
            default:
                $periodFilter = [];
        }
        /** End prepare filter by period */

        $_collection = $this->_addProductAttributesAndPrices($_collection);

        $_collection->getSelect()
            ->join(
                ['bestsellers' => $_collection->getTable('sales_bestsellers_aggregated_yearly')],
                'e.entity_id = bestsellers.product_id AND bestsellers.store_id = ' . $this->getStoreId(),
                ['qty_ordered', 'rating_pos', 'period']
            )
            ->group('bestsellers.product_id')
            ->order('rating_pos');

        /** Filter products collection by period */
        if ($periodFilter) {
            $from = $periodFilter['from'];
            $to = $periodFilter['to'];

            $_collection->getSelect()
                ->where("bestsellers.period >= '$from'")
                ->where("bestsellers.period <= '$to'");
        }
        /** End filter products collection by period */

        /** Configurable products from simple product added as well into best seller list */
        $_conn = $_collection->getResource()->getConnection();
        $select = $_conn->select('*')
            ->from($_collection->getTable('sales_bestsellers_aggregated_yearly AS sbay'))
            ->joinLeft(
                ['cpsl' => $_collection->getTable('catalog_product_super_link')],
                'sbay.product_id = cpsl.product_id',
                ['parent_id']
            )
            ->where('sbay.store_id = ' . $this->getStoreId())
            ->where('cpsl.parent_id IS NOT NULL');

        /** Filter products collection by period */
        if ($periodFilter) {
            $from = $periodFilter['from'];
            $to = $periodFilter['to'];

            $select->where("sbay.period >= '$from'")
                   ->where("sbay.period <= '$to'");
        }
        /** End filter products collection by period */

        $result = $_conn->fetchAll($select);
        $configurableParents = [];

        foreach ($result as $item) {
            $configurableParents[$item['parent_id']] = $item['rating_pos'];
        }

        $configurableProductsCollection = $this->_productCollectionFactory->create();
        $configurableProductsCollection->addAttributeToFilter('entity_id', ['in' => array_keys($configurableParents)]);
        $configurableProductsCollection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $configurableProductsCollection = $this->_addProductAttributesAndPrices($configurableProductsCollection);

        $items = clone $_collection;
        foreach ($_collection as $key => $item) {
            $_collection->removeItemByKey($key);
            $configurableParents[$key] = $item->getData('rating_pos');
        }

        asort($configurableParents);
        foreach ($configurableParents as $key => $ratingPos) {
            $item = $items->getItemById($key);
            if (!$item) {
                $item = $configurableProductsCollection->getItemById($key);
                if (!$item) {
                    continue;
                }
                $item->setData('rating_pos', $ratingPos);
            }

            if ($item) {
                $_collection->addItem($item);
            }
        }
        /** Configurable products from simple product added as well into best seller list */

        $_collection->addStoreFilter($this->getStoreId())->setCurPage(1);

        if ($limit && $limit > 0) {
            $_collection->setPageSize($limit);
        }

        return $_collection;
    }

    /**
     * Get sell slider products.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $_collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected function _getSellProductCollection($_collection)
    {
        $limit = $this->_getProductLimit('sell_products');
        $random = $this->_getRandomSort('sell_products');

        if (!$limit || $limit == 0) {
            return [];
        }

        $_collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());

        if ($random) {
            $allIds = $_collection->getAllIds();
            $candidateIds = $_collection->getAllIds();
            $randomIds = [];
            $maxKey = count($candidateIds) - 1;
            while (count($randomIds) <= count($allIds) - 1) {
                //@codingStandardsIgnoreLine
                $randomKey = mt_rand(0, $maxKey);
                $randomIds[$randomKey] = $candidateIds[$randomKey];
            }

            $_collection->addIdFilter($randomIds);
        }

        $_collection = $this->_addProductAttributesAndPrices($_collection)
            ->addAttributeToFilter(
                'special_from_date',
                ['date' => true, 'to' => $this->getEndOfDayDate()],
                'left'
            )
            ->addAttributeToFilter(
                'special_to_date',
                [
                    'or' => [
                        0 => ['date' => true, 'from' => $this->getStartOfDayDate()],
                        1 => ['is' => new Zend_Db_Expr('null')],
                    ]
                ],
                'left'
            )
            ->addAttributeToSort(
                'news_from_date',
                'desc'
            )
            ->addStoreFilter($this->getStoreId())
            ->setCurPage(1);

        if ($limit && $limit > 0) {
            $_collection->setPageSize($limit);
        }

        return $_collection;
    }

    /**
     * Get recently viewed slider products.
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $_collection
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getRecentlyViewedCollection($_collection)
    {
        $limit = $this->_getProductLimit('recently_viewed');
        $random = $this->_getRandomSort('recently_viewed');

        if ($limit == 0) {
            return [];
        }

        $_itemsCollection = $this->_viewProductsBlock->getItemsCollection();

        if ($random) {
            $allIds = $_itemsCollection->getAllIds();
            $candidateIds = $_itemsCollection->getAllIds();
            $randomIds = [];
            $maxKey = count($candidateIds) - 1;
            while (count($randomIds) <= count($allIds) - 1) {
                //@codingStandardsIgnoreLine
                $randomKey = mt_rand(0, $maxKey);
                $randomIds[$randomKey] = $candidateIds[$randomKey];
            }

            $_itemsCollection->addIdFilter($randomIds);
        }

        if ($limit && $limit > 0) {
            $_itemsCollection->setPageSize($limit);
        }

        return $_itemsCollection;
    }

    /**
     * *
     *
     * @param string $categoryId
     *
     * @return array|\Magento\Framework\Data\Collection\AbstractDb
     */
    protected function _getCustomCategoryCollection($categoryId)
    {
        $limit = $this->_getProductLimit('category_products');
        $random = $this->_getRandomSort('category_products');

        if ($limit == 0) {
            return [];
        }

        $category = $this->_categoryFactory->create()->load($categoryId);

        $_collection = $category->getProductCollection();
        $_collection->addAttributeToSelect('*');

        if ($random) {
            $_collection->getSelect()->order('RAND()');
        }

        if ($limit && $limit > 0) {
            $_collection->setPageSize($limit);
        }

        return $_collection;
    }

    /**
     * Get related slider products.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollectionRelated()
    {
        if (!$this->_currentProduct) {
            return [];
        }

        return $this->getRelatedProducts($this->_currentProduct);
    }

    /**
     * Retrieve array of related products.
     *
     * @param object $currentProduct
     *
     * @return array
     */
    public function getRelatedProducts($currentProduct)
    {
        if (!$currentProduct->hasRelatedProducts()) {
            $products = [];
            $_collection = $currentProduct->getRelatedProductCollection();
            $_collection->addAttributeToSelect('*');
            foreach ($_collection as $product) {
                $products[] = $product;
            }
            $currentProduct->setRelatedProducts($products);
        }

        return $currentProduct->getData('related_products');
    }

    /**
     * Get up-sell slider products.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollectionUpSell()
    {
        if (!$this->_currentProduct) {
            return [];
        }
        return $this->getUpSellProducts($this->_currentProduct);
    }

    /**
     * Retrieve array of up sell products.
     *
     * @param object $currentProduct
     *
     * @return array
     */
    public function getUpSellProducts($currentProduct)
    {
        if (!$currentProduct->hasUpSellProducts()) {
            $products = [];
            $_collection = $currentProduct->getUpSellProductCollection();
            $_collection->addAttributeToSelect('*');
            foreach ($_collection as $product) {
                $products[] = $product;
            }
            $currentProduct->setUpSellProducts($products);
        }

        return $currentProduct->getData('up_sell_products');
    }

    /**
     * Get cross-sell slider products.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollectionCrossSell()
    {
        if (!$this->_currentProduct) {
            return [];
        }

        return $this->getCrossSellProducts($this->_currentProduct);
    }

    /**
     * Retrieve array of cross sell products
     *
     * @param object $currentProduct
     *
     * @return array
     */
    public function getCrossSellProducts($currentProduct)
    {
        if (!$currentProduct->hasCrossSellProducts()) {
            $products = [];
            $_collection = $currentProduct->getCrossSellProductCollection();
            $_collection->addAttributeToSelect('*');
            foreach ($_collection as $product) {
                $products[] = $product;
            }
            $currentProduct->setCrossSellProducts($products);
        }

        return $currentProduct->getData('cross_sell_products');
    }

    /**
     * Retrieve the products limit based on type.
     *
     * @param string $type
     * @return int
     */
    protected function _getProductLimit($type)
    {
        return $this->_helperProducts->getProductLimit($type);
    }

    /**
     * Retrieve the products random sort flag based on type.
     *
     * @param string $type
     * @return mixed
     */
    protected function _getRandomSort($type)
    {
        return $this->_helperProducts->getRandomSort($type);
    }

    /**
     * Get start of day date.
     *
     * @return string
     */
    public function getStartOfDayDate()
    {
        return $this->_localeDate->date()->setTime(0, 0, 0)->format('Y-m-d H:i:s');
    }

    /**
     * Get end of day date.
     *
     * @return string
     */
    public function getEndOfDayDate()
    {
        return $this->_localeDate->date()->setTime(23, 59, 59)->format('Y-m-d H:i:s');
    }

    /**
     * Retrieve the current store id.
     *
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * *
     *
     * @return mixed
     */
    public function isHoverImageEnabled()
    {
        return $this->_helperCustom->isHoverImageEnabled();
    }

    /**
     * *
     *
     * @param string $category
     *
     * @return string
     */
    protected function _getCategoryIdFrom($category)
    {
        $value = explode('/', $category);
        $categoryId = false;

        if (isset($value[0]) && isset($value[1]) && $value[0] == 'category') {
            $categoryId = $value[1];
        }

        return $categoryId;
    }
}
