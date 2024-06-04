<?php

namespace WeltPixel\AdvanceCategorySorting\Plugin\Frontend\Model\Elastic;

use Magento\Elasticsearch\Model\Adapter\BatchDataMapper\ProductDataMapper as ProductDataMapperAlias;
use Magento\Elasticsearch\Model\Adapter\FieldType\Date as DateFieldType;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

class ProductDataMapper
{

    /**
     * @var DateFieldType
     */
    private $dateFieldType;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var AdapterInterface
     */
    protected $connection;

    /**
     * @var string
     */
    protected $productEntityTable;

    /**
     * @var string
     */
    protected $reviewEntityTable;

    /**
     * @var string
     */
    protected $ratingVoteTable;

    /**
     * @var string
     */
    protected $salesOrderItemTable;

    /**
     * @var string
     */
    protected $salesOrderTable;

    /**
     * @var string
     */
    protected $salesBestsellerAggregateTable;

    /**
     * @var bool
     */
    protected $useSalesAggregateTable;

    /**
     * @param DateFieldType $dateFieldType
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        DateFieldType $dateFieldType,
        ResourceConnection $resourceConnection
    ) {
        $this->dateFieldType = $dateFieldType;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Map index data for using in search engine metadata
     *
     * @param ProductDataMapperAlias $subject
     * @param array $result
     * @param array $documentData
     * @param int $storeId
     * @param array $context
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function afterMap(
        ProductDataMapperAlias $subject,
        $result,
        array $documentData,
        $storeId,
        array $context = []
    ) {
        $this->connection = $this->resourceConnection->getConnection();
        $this->productEntityTable = $this->resourceConnection->getTableName('catalog_product_entity');
        $this->reviewEntityTable = $this->resourceConnection->getTableName('review_entity_summary');
        $this->ratingVoteTable = $this->resourceConnection->getTableName('rating_option_vote_aggregated');
        $this->salesOrderItemTable = $this->resourceConnection->getTableName('sales_order_item');
        $this->salesOrderTable = $this->resourceConnection->getTableName('sales_order');
        $this->salesBestsellerAggregateTable = $this->resourceConnection->getTableName(
            'sales_bestsellers_aggregated_yearly'
        );

        if (!isset($this->useSalesAggregateTable)) {
            $query = $this->connection->select()->from(
                $this->salesBestsellerAggregateTable,
                ['rows_count' => new \Zend_Db_Expr('COUNT(*)')]
            );
            $countResult = $this->connection->fetchOne($query);

            if ($countResult) {
                $this->useSalesAggregateTable = true;
            } else {
                $this->useSalesAggregateTable = false;
            }
        }

        foreach ($result as $productId => $indexData) {
            $additionalData = $this->getAdditionalData($productId, $storeId);
            $result[$productId] = array_merge_recursive(
                $result[$productId],
                $additionalData
            );
        }

        return $result;
    }

    /**
     * Get Additional Data
     *
     * @param int $productId
     * @param int $storeId
     * @return array
     */
    protected function getAdditionalData($productId, $storeId)
    {
        $additionalData = [];
        $productCreatedAt = $this->getProductCreatedAt($productId, $storeId);
        $productRates = $this->getProductRates($productId, $storeId);
        $productReview = $this->getProductReview($productId, $storeId);
        $productSales = $this->getProductSales($productId, $storeId);

        $additionalData['created_at']  = $productCreatedAt;
        $additionalData['wp_sortby_new']  = $productCreatedAt;
        $additionalData['wp_sortby_rates']  = $productRates;
        $additionalData['wp_sortby_review']  = $productReview;
        $additionalData['wp_sortby_sales']  = $productSales;

        return $additionalData;
    }

    /**
     * Get Product CreatedAt
     *
     * @param int $productId
     * @param int $storeId
     * @return string|null
     */
    protected function getProductCreatedAt($productId, $storeId)
    {
        $select = $this->connection->select()
            ->from(
                $this->productEntityTable,
                ['created_at']
            )
            ->where('entity_id = ?', $productId);
        $productCreatedAt = $this->connection->fetchOne($select);

        return $this->dateFieldType->formatDate($storeId, $productCreatedAt);
    }

    /**
     * Get Product Rates
     *
     * @param int $productId
     * @param int $storeId
     * @return int|string
     */
    protected function getProductRates($productId, $storeId)
    {
        $query = $this->connection->select()->from(
            $this->ratingVoteTable,
            ['percent_approved']
        )
            ->where('entity_pk_value = ?', $productId)
            ->where('store_id = ?', $storeId);
        $productRates = $this->connection->fetchOne($query);

        return ($productRates) ? $productRates : 0;
    }

    /**
     * Get Product Review
     *
     * @param int $productId
     * @param int $storeId
     * @return mixed
     */
    protected function getProductReview($productId, $storeId)
    {
        $query = $this->connection->select()->from(
            $this->reviewEntityTable,
            ['reviews_count']
        )
        ->where('entity_pk_value = ?', $productId)
        ->where('store_id = ?', $storeId);
        $productReview = $this->connection->fetchOne($query);

        return ($productReview) ? $productReview : 0;
    }

    /**
     * Get Product Sales
     *
     * @param int $productId
     * @param int $storeId
     * @return mixed
     */
    protected function getProductSales($productId, $storeId)
    {
        if ($this->useSalesAggregateTable) {
            $query = $this->connection->select()->from(
                $this->salesBestsellerAggregateTable,
                ['rows_count' => new \Zend_Db_Expr('COUNT(*)')]
            )
            ->where('product_id = ?', $productId)
            ->where('store_id = ?', $storeId);
            $productSales = $this->connection->fetchOne($query);
        } else {
            $sumCond = new \Zend_Db_Expr(
                "SUM(salesitem.qty_ordered - salesitem.qty_refunded)"
            );
            $whereClause = 'sales.status = :status AND salesitem.store_id = :store_id'
                . ' AND salesitem.product_id = :product_id';
            $whereParams = [
                ':status' => 'complete',
                ':store_id' => $storeId,
                ':product_id' => $productId,
            ];
            $select = $this->connection->select()->from(
                ['salesitem' => $this->salesOrderItemTable],
                ['total_amount' => $sumCond]
            )->joinLeft(
                ['sales' => $this->salesOrderTable],
                "salesitem.order_id = sales.entity_id"
            )->where($whereClause);
            $productSales = $this->connection->fetchOne($select, $whereParams);
        }
        return ($productSales) ? $productSales : 0;
    }
}
