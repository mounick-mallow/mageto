<?php

namespace LuxuryUnlimited\SaleProducts\Cron;

use LuxuryUnlimited\SaleProducts\Helper\Data;
use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class SaleProductsCron
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
     * @throws
     */
    public function execute()
    {
        $log = ['called at' => date('Y-m-d H:i:s')];
        $this->logger->info(print_r($log)); // @codingStandardsIgnoreLine

        $batchCount = $this->_helper->getSaleProductsCronBatchCount();
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

                try {
                    $productCollectionCount = $productCollection->count();
                    $productCollection->load();
                } catch (\Exception $e) {
                    $this->logger->info(__('Error during loading product collection'));
                    $this->logger->error($e->getMessage());

                    $productCollectionCount = 0;
                }

                if ($productCollectionCount > 0) {
                    foreach ($productCollection as $prod) {
                        try {
                            $productId = $prod->getId();
                            $isAddToSaleProducts =  $this->checkIsProductAddedToSales($productId);

                            if ($isAddToSaleProducts) {
                                $this->addCategoryMappedProducts($prod);
                            } else {
                                $this->removeProductFromCategory($prod);
                            }
                            ++$overAllCount;
                        } catch (\Exception $e) {
                            $this->logger->info(__('Can\'t update the product.'));
                            $this->logger->error($e->getMessage());
                        }
                    }

                    $lastProcessedPage += $pageSize;

                    try {
                        $this->_helper->setConfigData(
                            Data::OVER_ALL_COUNT,
                            $overAllCount
                        );
                        $this->_helper->setConfigData(
                            Data::LAST_PROCESSED_PAGE,
                            $lastProcessedPage
                        );
                    } catch (CouldNotSaveException $e) {
                        $this->logger->info(__('Could not save config'));
                        $this->logger->error($e->getMessage());
                    } catch (\Exception $e) {
                        $this->logger->info(__('An unexpected error occurred'));
                        $this->logger->error($e->getMessage());
                    }

                } else {
                    $this->logger->info(__('SaleProductsCron : no more products'));
                }
            }
        } else {
            # no processed date - calling for the first time
            # set today as last processed Date
            try {
                $this->_helper->setConfigData(Data::LAST_PROCESSED_DATE, $today);
                $this->_helper->setConfigData(Data::OVER_ALL_COUNT, '0');
                $this->_helper->setConfigData(Data::LAST_PROCESSED_PAGE, '0');
            } catch (CouldNotSaveException $e) {
                $this->logger->info(__('Could not save config'));
                $this->logger->error($e->getMessage());
            } catch (\Exception $e) {
                $this->logger->info(__('An unexpected error occurred'));
                $this->logger->error($e->getMessage());
            }

            $this->clearCategoryUnmappedProducts();
        }
        $this->logger->info(__('SaleProductsCron : done'));
    }

    /**
     * *
     */
    public function resetConfig()
    {
        try {
            $today = $this->_helper->getStoreDate();
            $this->_helper->setConfigData(Data::LAST_PROCESSED_DATE, $today);
            $this->_helper->setConfigData(Data::OVER_ALL_COUNT, '0');
            $this->_helper->setConfigData(Data::LAST_PROCESSED_PAGE, '0');
        } catch (CouldNotSaveException $e) {
            $this->logger->info(__('Could not save config'));
            $this->logger->error($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->info(__('An unexpected error occurred'));
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * *
     */
    public function clearCategoryUnmappedProducts()
    {
        try {
            $categoryId = $this->_helper->getSaleProductsCategory();
            $category = $this->_helper->getCategoryById($categoryId);
        } catch (\Exception $e) {
            $this->logger->info(__('Requested sales category doesn\'t exist'));
            $this->logger->error($e->getMessage());
            return;
        }

        if (!$category->getId()) {
            $this->logger->info(__('Requested sales category doesn\'t exist'));
            return;
        }

        try {
            $products = $this->_helper->getProductCollectionByCategories($category);
        }  catch (\Exception $e) {
            $this->logger->info(__('Couldn\'t get a list of products by category'));
            $this->logger->error($e->getMessage());
            return;
        }

        if (!empty($products)) {
            foreach ($products as $product) {
                try {
                    $productId = $product->getId();
                    $sku = $product->getSku();
                    $categories = $this->_helper->getCategoryIds($productId);
                    if (!$this->checkIsProductAddedToSales($productId)) {
                        if (($key = array_search($categoryId, $categories)) !== false) {
                            unset($categories[$key]);
                        }
                        if ($categories) {
                            $this->_helper->assignProductToCategories($sku, $categories);
                        }
                    }
                } catch (\Exception $e) {
                    $this->logger->info(__('An unexpected error occurred during saving the product.'));
                    $this->logger->error($e->getMessage());
                }
            }
        }
    }

    /**
     * Remove Product From Category
     *
     * @param Product $product
     * @return void
     */
    public function removeProductFromCategory($product)
    {
        $categoryId = $this->_helper->getSaleProductsCategory();
        $sku = $product->getSku();
        $productId = $product->getId();
        $categories = $this->_helper->getCategoryIds($productId);
        if (in_array($categoryId, $categories)) {
            if (($key = array_search($categoryId, $categories)) !== false) {
                unset($categories[$key]);
            }
            if ($categories) {
                try {
                    $this->_helper->assignProductToCategories($sku, $categories);
                } catch (\Exception $e) {
                    $this->logger->info(__('Error during removing product from category'));
                    $this->logger->error($e->getMessage());
                }
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
        $categoryId = $this->_helper->getSaleProductsCategory();

        $productId = $product->getId();
        $categories = $this->_helper->getCategoryIds($productId);
        if ($categoryId) {
            array_push($categories, $categoryId);
        }

        if ($categories) {
            $sku = $product->getSku();
            try {
                $this->_helper->assignProductToCategories($sku, $categories);
            } catch (\Exception $e) {
                $this->logger->info(__('Error during updating product categories'));
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * Check Is Product Added To Sales
     *
     * @param string $productId
     *
     * @return int
     */
    public function checkIsProductAddedToSales($productId)
    {
        $connection = $this->resourceConnection->getConnection();
        $attributeId =  $this->getAttributeId('add_to_sale_products');

        if (!empty($attributeId) && !empty($productId)) {
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
        } else {
            $this->logger->info(__('Error during checking if product in sales'));
        }
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
