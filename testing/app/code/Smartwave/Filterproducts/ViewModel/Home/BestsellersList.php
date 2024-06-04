<?php

namespace Smartwave\Filterproducts\ViewModel\Home;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\DB\Select;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Url\Helper\Data;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Smartwave\Filterproducts\Model\ListProductExtend;

/**
 * Class View Model BestsellerList
 */
class BestsellersList extends ListProductExtend implements ArgumentInterface
{
    /**
     * @var Collection
     */
    protected Collection $collection;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var ResourceConnection
     */
    protected ResourceConnection $resource;

    /**
     * @var CategoryFactory
     */
    private CategoryFactory $categoryFactory;

    /**
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CategoryFactory $categoryFactory
     * @param Data $urlHelper
     * @param Collection $collection
     * @param ResourceConnection $resource
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        CategoryFactory $categoryFactory,
        Data $urlHelper,
        Collection $collection,
        ResourceConnection $resource,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->collection = $collection;
        $this->resource = $resource;

        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * Function Get Product Collection
     *
     * @return Collection
     * @throws NoSuchEntityException
     *
     * //phpcs:ignore Generic.Files.LineLength.TooLong
     */
    public function getProductCollection(): Collection
    {
        return $this->getProducts();
    }

    /**
     * Function Get Product
     *
     * @return Collection
     * @throws NoSuchEntityException
     */
    public function getProducts(): Collection
    {
        $count = $this->getProductCount();
        $categoryId = $this->getData("category_id");
        $collection = clone $this->collection;
        $collection->clear()->getSelect()->reset(
            Select::WHERE
        )->reset(
            Select::ORDER
        )->reset(
            Select::LIMIT_COUNT
        )->reset(
            Select::LIMIT_OFFSET
        )->reset(
            Select::GROUP
        )->reset(
            Select::COLUMNS
        )->reset(
            'from'
        );
        $connection  = $this->resource->getConnection();
        $collection->getSelect()->join(['e' => $connection->getTableName('catalog_product_entity')], '');

        if (!$categoryId) {
            $categoryId = $this->_storeManager->getStore()->getRootCategoryId();
        }
        $category = $this->categoryFactory->create()->load($categoryId);
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('thumbnail')
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->addUrlRewrite()
            ->addCategoryFilter($category);
        $collection->getSelect()
            ->joinLeft(
                ['soi' => $connection->getTableName('sales_order_item')],
                'soi.product_id = e.entity_id',
                ['SUM(soi.qty_ordered) AS ordered_qty']
            )
            ->join(
                ['order' => $connection->getTableName('sales_order')],
                "order.entity_id = soi.order_id",
                ['order.state']
            )
            ->where("order.state <> 'canceled' and soi.parent_item_id IS NULL AND soi.product_id IS NOT NULL")
            ->group('soi.product_id')
            ->order('ordered_qty DESC')
            ->limit($count);

        return $collection;
    }

    /**
     * Function Get Loaded Product Collection
     *
     * @return Collection
     * @throws NoSuchEntityException
     *
     * //phpcs:ignore Generic.Files.LineLength.TooLong
     */
    public function getLoadedProductCollection(): Collection
    {
        return $this->getProducts();
    }
}
