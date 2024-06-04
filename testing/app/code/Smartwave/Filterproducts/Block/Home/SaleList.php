<?php

namespace Smartwave\Filterproducts\Block\Home;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class SaleList extends \Magento\Catalog\Block\Product\ListProduct {

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_collection;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Constructor
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param \Magento\Framework\Url\Helper\Data $urlHelper
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param DateTime $dateTime
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Framework\App\ResourceConnection $resource,
        DateTime $dateTime,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->_collection = $collection;
        $this->_resource = $resource;
        $this->dateTime = $dateTime;
        parent::__construct($context, $postDataHelper, $layerResolver, $categoryRepository, $urlHelper, $data);
    }

    protected function _getProductCollection() {
        return $this->getProducts();
    }

    public function getProducts() {
        $count = $this->getProductCount();
        $category_id = $this->getData("category_id");
        $collection = clone $this->_collection;
        $collection->clear()->getSelect()->reset(\Magento\Framework\DB\Select::WHERE)->reset(\Magento\Framework\DB\Select::ORDER)->reset(\Magento\Framework\DB\Select::LIMIT_COUNT)->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET)->reset(\Magento\Framework\DB\Select::GROUP);

        if(!$category_id) {
            $category_id = $this->_storeManager->getStore()->getRootCategoryId();
        }
        $category = $this->categoryRepository->get($category_id);
        $now = $this->date->gmtDate('Y-m-d');
        if(isset($category) && $category) {
            $collection->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('small_image')
                ->addAttributeToSelect('thumbnail')
                ->addAttributeToSelect('special_from_date')
                ->addAttributeToSelect('special_to_date')
                ->addAttributeToFilter('special_price', ['neq' => ''])
                ->addAttributeToFilter([
                    [
                        'attribute' => 'special_from_date',
                        'lteq' => $now,
                        'date' => true,
                    ],
                    [
                        'attribute' => 'special_to_date',
                        'gteq' => $now,
                        'date' => true,
                    ]
                ]);
        } else {
            $collection->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('image')
                ->addAttributeToSelect('small_image')
                ->addAttributeToSelect('thumbnail')
                ->addAttributeToFilter('special_price', ['neq' => ''])
                ->addAttributeToSelect('special_from_date')
                ->addAttributeToSelect('special_to_date')
                ->addAttributeToFilter([
                    [
                        'attribute' => 'special_from_date',
                        'lteq' => $now,
                        'date' => true,
                    ],
                    [
                        'attribute' => 'special_to_date',
                        'gteq' => $now,
                        'date' => true,
                    ]
                ]);
        }

        $collection->getSelect()
                ->limit($count);

        return $collection;
    }

    public function getLoadedProductCollection() {
        return $this->getProducts();
    }

    public function getProductCount() {
        $limit = $this->getData("product_count");
        if(!$limit)
            $limit = 10;
        return $limit;
    }
}
