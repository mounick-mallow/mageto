<?php

namespace LuxuryUnlimited\Checkout\ViewModel;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\Config\Source\Product\Thumbnail;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Model\Product\Configuration\Item\ItemProductResolver;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

class ItemRendererImage implements ArgumentInterface
{
    /**
     * @var Image
     */
    private Image $imageHelper;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param Image                      $imageHelper
     * @param ProductRepositoryInterface $productRepository
     * @param ScopeConfigInterface       $scopeConfig
     */
    public function __construct(
        Image $imageHelper,
        ProductRepositoryInterface $productRepository,
        ScopeConfigInterface $scopeConfig,
    ) {
        $this->imageHelper = $imageHelper;
        $this->productRepository = $productRepository;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Image Url
     *
     * @param object $item
     * @return string
     */
    public function getImageUrl($item): string
    {
        $product = $this->resolveProductFromItem($item);
        if (!$product) {
            return '';
        }
        $imageId = 'product_base_image';
        return $this->imageHelper->init($product, $imageId)
            ->setImageFile($product->getImage())
            ->getUrl();
    }

    /**
     * Resolve Product From Item
     *
     * @param object $item
     * @return ProductInterface
     */
    private function resolveProductFromItem($item)
    {
        $finalProduct = null;
        if ($item instanceof \Magento\Sales\Model\Order\Item) {
            $finalProduct = $item->getProduct();
            if ($finalProduct && $finalProduct->getTypeId() == Configurable::TYPE_CODE) {
                $childProduct = $this->getChildProduct($item);
                if ($childProduct !== null && $this->isUseChildProduct($childProduct)) {
                    $finalProduct = $childProduct;
                }
            }
        }
        return $finalProduct;
    }

    /**
     * Get Child Product
     *
     * @param object $item
     * @return ProductInterface|null
     */
    private function getChildProduct($item)
    {
        $simpleSku = $item->getProductOptionByCode('simple_sku');
        if (!$simpleSku) {
            return null;
        }
        return $this->getProductByData($simpleSku);
    }

    /**
     * Get Product By Data
     *
     * @param string $value
     * @param string $key
     * @return ProductInterface|null
     */
    private function getProductByData($value, string $key = 'sku')
    {
        if ($key === 'sku') {
            try {
                return $this->productRepository->get($value);
            } catch (NoSuchEntityException $exception) {
                return null;
            }
        } elseif ($key === 'id') {
            try {
                return $this->productRepository->getById($value);
            } catch (NoSuchEntityException $exception) {
                return null;
            }
        }
        return null;
    }

    /**
     * Is need to use child product
     *
     * @param Product $childProduct
     * @return bool
     */
    private function isUseChildProduct($childProduct): bool
    {
        $configValue = $this->scopeConfig->getValue(
            ItemProductResolver::CONFIG_THUMBNAIL_SOURCE,
            ScopeInterface::SCOPE_STORE
        );
        $childThumb = $childProduct->getData('thumbnail');
        return $configValue !== Thumbnail::OPTION_USE_PARENT_IMAGE
            && $childThumb !== null
            && $childThumb !== 'no_selection';
    }
}
