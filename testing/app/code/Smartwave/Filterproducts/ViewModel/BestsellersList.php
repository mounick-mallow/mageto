<?php

namespace Smartwave\Filterproducts\ViewModel;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Data\Helper\PostHelper;
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
     * @var Visibility
     */
    protected Visibility $catalogProductVisibility;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $productCollectionFactory;

    /**
     * @var CategoryFactory
     */
    protected CategoryFactory $categoryFactory;

    /**
     * @var ResourceConnection
     */
    private ResourceConnection $resource;

    /**
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Data $urlHelper
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param CategoryFactory $categoryFactory
     * @param ResourceConnection $resource
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        Data $urlHelper,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        CategoryFactory $categoryFactory,
        ResourceConnection $resource,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
        $this->productCollectionFactory = $productCollectionFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->categoryFactory = $categoryFactory;
        $this->resource = $resource;
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
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        $collection = $this->_addProductAttributesAndPrices($collection)->addStoreFilter();
        if (!$categoryId) {
            $categoryId = $this->_storeManager->getStore()->getRootCategoryId();
        }
        $category = $this->categoryFactory->create()->load($categoryId);
        $connection  = $this->resource->getConnection();
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('image')
            ->addAttributeToSelect('small_image')
            ->addAttributeToSelect('thumbnail')
            ->addAttributeToSelect($this->_catalogConfig->getProductAttributes())
            ->addUrlRewrite()
            ->addAttributeToFilter('is_saleable', [1], 'left')
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
     * Function Get Add To Cart Post Param
     *
     * @param Product $product
     * @return array
     *
     * //phpcs:ignore Generic.Files.LineLength.TooLong
     */
    public function getAddToCartPostParams(Product $product): array
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
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
