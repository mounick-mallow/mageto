<?php

namespace LuxuryUnlimited\MostPopular\Cron;

use LuxuryUnlimited\MostPopular\Helper\Data;
use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;

class MostPopularCron
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Data $helper
     */
    private $_helper;

    /**
     * @var AttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @param LoggerInterface $logger
     * @param Data $_helper
     * @param AttributeRepositoryInterface $attributeRepository
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        LoggerInterface $logger,
        Data $_helper,
        AttributeRepositoryInterface $attributeRepository,
        ResourceConnection $resourceConnection
    ) {
        $this->logger = $logger;
        $this->_helper = $_helper;
        $this->attributeRepository = $attributeRepository;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Write to system.log
     *
     * @return void
     */
    public function execute()
    {
        $log = ['called at' => date('Y-m-d H:i:s')];
        $this->logger->info(print_r($log)); // @codingStandardsIgnoreLine

        $batchCount = $this->_helper->getMostPopularCronBatchCount();
        $lastProcessedPage = $this->_helper->getLastProcessedPage();
        $today = $this->_helper->getStoreDate();

        $lastProcessedDateData = $this->_helper->getConfigCollection(
            Data::LAST_PROCESSED_DATE
        );
        $lastProcessedDate = $lastProcessedDateData->getFirstItem()->getValue();

        $lastProcessedPageData = $this->_helper->getConfigCollection(
            Data::LAST_PROCESSED_PAGE
        );
        $lastProcessedPage = $lastProcessedPageData->getFirstItem()
            ->getValue();

        $overAllCountData = $this->_helper->getConfigCollection(
            Data::OVER_ALL_COUNT
        );
        $overAllCount = $overAllCountData->getFirstItem()->getValue();

        if ($lastProcessedDate) {
            $previousDate = $this->_helper->getPreviousDate();
            if ($lastProcessedDate == $previousDate) {
                # cron was run yesterday
                # need to change last processed date
                # need to clear already mapped products if not exist
                $this->clearCategoryUnmappedProducts();
                $this->resetConfig();
            } else {
                # cron run already today
                # need to get the batch count
                # need to get already processed page
                # need to get the processed count
                $currentPage = $lastProcessedPage;
                $pageSize = $batchCount;
                $productCollection =  $this->_helper->getProducts();
                $productCollection->getSelect()->limit($pageSize, $currentPage);
                if (count($productCollection)) {
                    foreach ($productCollection as $prod) {
                        $productId = $prod->getId();
                        $isAddToMostPopular =  $this->checkIsProductAddedToPopular(
                            $productId
                        );
                        if ($isAddToMostPopular) {
                            $this->addCategoryMappedProducts($prod);
                        } else {
                            $this->removeProductFromCategroy($prod);
                        }
                        ++$overAllCount;
                    }
                    $lastProcessedPage += $pageSize;
                    $this->_helper->setConfigData(
                        Data::OVER_ALL_COUNT,
                        $overAllCount
                    );
                    $this->_helper->setConfigData(
                        Data::LAST_PROCESSED_PAGE,
                        $lastProcessedPage
                    );
                } else {
                    $this->logger->debug('MostPopularCron : no more products');
                }
            }
        } else {
            # no processed date - calling for the first time
            # set today as last processed Date
            $this->_helper->setConfigData(Data::LAST_PROCESSED_DATE, $today);
            $this->_helper->setConfigData(Data::OVER_ALL_COUNT, '0');
            $this->_helper->setConfigData(Data::LAST_PROCESSED_PAGE, '0');
            $this->clearCategoryUnmappedProducts();
        }
        $this->logger->debug('MostPopularCron : done');
    }

    /**
     * Reset Config
     *
     * @return void
     */
    public function resetConfig()
    {
        $today = $this->_helper->getStoreDate();
        $this->_helper->setConfigData(Data::LAST_PROCESSED_DATE, $today);
        $this->_helper->setConfigData(Data::OVER_ALL_COUNT, '0');
        $this->_helper->setConfigData(Data::LAST_PROCESSED_PAGE, '0');
    }

    /**
     * Clear Category Unmapped Products
     *
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function clearCategoryUnmappedProducts()
    {
        $categoryId = $this->_helper->getMostPopularCategory();
        $category = $this->_helper->getCategoryById($categoryId);
        $products = $this->_helper->getProductCollectionByCategories(
            $category
        );
        foreach ($products as $product) {
            $productId = $product->getId();
            $sku = $product->getSku();
            $categories = $this->_helper->getCategoryIds($productId);
            if (!$this->checkIsProductAddedToPopular($productId)) {
                if (($key = array_search($categoryId, $categories)) !== false) {
                    unset($categories[$key]);
                }
                if ($categories) {
                    $this->_helper->assignProductToCategories($sku, $categories);
                }
            }
        }
    }

    /**
     * Remove Product From Categroy
     *
     * @param Product $product
     * @return void
     */
    public function removeProductFromCategroy($product)
    {
        $categoryId = $this->_helper->getMostPopularCategory();
        $sku = $product->getSku();
        $productId = $product->getId();
        $categories = $this->_helper->getCategoryIds($productId);
        if (in_array($categoryId, $categories)) {
            if (($key = array_search($categoryId, $categories)) !== false) {
                unset($categories[$key]);
            }
            if ($categories) {
                $this->_helper->assignProductToCategories($sku, $categories);
            }
        }
    }

    /**
     * Add Category Mapped Products
     *
     * @param Product $product
     * @return void
     */
    public function addCategoryMappedProducts($product)
    {
        $categoryId = $this->_helper->getMostPopularCategory();
        $productId = $product->getId();
        $categories = $this->_helper->getCategoryIds($productId);
        if ($categoryId) {
            array_push($categories, $categoryId);
        }

        if ($categories) {
            $sku = $product->getSku();
            $this->_helper->assignProductToCategories($sku, $categories);
        }
    }

    /**
     * Check Is Product Added To Popular
     *
     * @param mixed $productId
     * @return int|mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function checkIsProductAddedToPopular($productId)
    {
        $connection = $this->resourceConnection->getConnection();
        $attributeId=  $this->getAttributeId('add_to_sale_products');
        $data = $connection->select()->from(
            'catalog_product_entity_int'
        )->where(
            'attribute_id = ?',
            $attributeId
        )->where(
            'entity_id = ?',
            $productId
        );
        $row =  $connection->fetchRow($data);
        return (isset($row['value'])) ? $row['value'] : 0;
    }

    /**
     * Get Attribute Id
     *
     * @param string $code
     * @return int|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAttributeId($code)
    {
        $attribute = $this->attributeRepository->get(Product::ENTITY, $code);
        return $attribute->getAttributeId();
    }
}
