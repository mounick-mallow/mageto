<?php

/**
 * Ascure_Helperblock
 *
 * @copyright   Copyright (c) 2023 IdeaInYou
 * @author      RuslanP <ruslan.p@ideainyou.com>
 */

namespace Ascure\Helperblock\ViewModel;

use Magento\Catalog\Model\Product as ProductModel;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Psr\Log\LoggerInterface;

/**
 * Class View Model get "Size Chart" link
 */
class Product implements ArgumentInterface
{
    private const SIZE_CHART_URL = 'size_chart_url';

    /**
     * @var ProductRepository
     */
    private ProductRepository $productRepository;

    /**
     * @var AttributeFactory
     */
    private AttributeFactory $attributeFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Product constructor.
     *
     * @param ProductRepository $productRepository
     * @param AttributeFactory $attributeFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ProductRepository $productRepository,
        AttributeFactory $attributeFactory,
        LoggerInterface $logger
    ) {
        $this->productRepository = $productRepository;
        $this->attributeFactory = $attributeFactory;
        $this->logger = $logger;
    }

    /**
     * Get Size Value
     *
     * @param int $productId
     * @return string
     */
    public function getSizeValue(int $productId): string
    {
        try {
            $product = $this->productRepository->getById($productId);
            $sizeChartUrl = $product->getCustomAttribute(self::SIZE_CHART_URL);
            if ($sizeChartUrl) {
                return $sizeChartUrl->getValue();
            } else {
                return '';
            }
        } catch (NoSuchEntityException $e) {
            $this->logger->error(
                $e->getMessage(),
                [
                    'product_id' => $productId
                ]
            );
            return '';
        }
    }

    /**
     * Get attribute label
     *
     * @param string $attributeCode
     * @return string
     */
    public function getAttributeLabel($attributeCode): string
    {
        try {
            $attributeFactory = $this->attributeFactory->create();
            $attribute = $attributeFactory->loadByCode(ProductModel::ENTITY, $attributeCode);

            return $attribute->getStoreLabel();
        } catch (LocalizedException $e) {
            $this->logger->error(
                $e->getMessage(),
                [
                    'attribute_code' => $attributeCode
                ]
            );
            return '';
        }
    }
}
