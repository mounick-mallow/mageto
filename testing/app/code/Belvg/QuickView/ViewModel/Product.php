<?php

namespace Belvg\QuickView\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;

class Product implements ArgumentInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    public ProductRepositoryInterface $productRepository;

    /**
     * @var ImageHelper
     */
    public ImageHelper $imageHelper;

    /**
     *
     * @param ProductRepositoryInterface $productRepository
     * @param ImageHelper $imageHelper
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ImageHelper $imageHelper
    ) {
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
    }

    /**
     * Return product object based on id
     *
     * @param int $productId
     * @return ProductInterface|string
     */
    public function getProduct(int $productId): string|ProductInterface
    {
        try {
            return  $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return $e->getMessage();
        }
    }

    /**
     * Return Images based on the product
     *
     * @param ProductInterface $product
     * @return array
     */
    public function getImages(ProductInterface $product): array
    {
        $images = [];
        foreach ($product->getMediaGalleryEntries() as $entry) {
            $images[] = $this->imageHelper->init($product, 'cart_cross_sell_products')
                ->setImageFile($entry->getFile())
                ->getUrl();
        }

        return $images;
    }
}
