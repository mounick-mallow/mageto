<?php

namespace Belvg\Products\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Framework\Exception\NoSuchEntityException;

class Data extends  AbstractHelper
{
    public ProductRepositoryInterface $productRepository;

    public ImageHelper $imageHelper;

    public ListProduct $productViewBlock;


    /**
     *
     * @param Context $context
     * @param ProductRepositoryInterface $productRepository
     * @param ImageHelper $imageHelper
     * @param ListProduct $productViewBlock
     */
    public function __construct(
        Context $context,
        ProductRepositoryInterface $productRepository,
        ImageHelper $imageHelper,
        ListProduct $productViewBlock
    ) {
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
        $this->productViewBlock = $productViewBlock;
        parent::__construct($context);
    }

    /**
     *
     * @param int $productId
     * @return \Magento\Catalog\Api\Data\ProductInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProduct(int $productId)
    {
        return $this->productRepository->getById($productId);
    }


    /**
     *
     * @param $product
     * @param string $imageType
     * @return string|null
     */
    public function getProductImage($product, string $imageType): ?string
    {
        return  $this->imageHelper
            ->init($product, $imageType)
            ->setImageFile($product->getImage())
            ->getUrl();
    }

    /**
     *
     * @param int $productId
     * @param string $type
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getImageByProductId(int $productId, string $type): ?string
    {
        $product = $this->getProduct($productId);
        return $this->getProductImage($product, $type);
    }


    /**
     *
     * @param $product
     * @return array|null
     */
    public function getAddToCartPostParams($product): ?array
    {
        return $this->productViewBlock->getAddToCartPostParams($product);
    }
}
