<?php

namespace LuxuryUnlimited\SaleProducts\Block;

// use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use LuxuryUnlimited\SaleProducts\Helper\Data;

class SaleProducts extends \Magento\Catalog\Block\Product\ListProduct
{
    /**
     * @var Data $helper
     */
    private $_helper;

    /**
     * *
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param UrlHelper $urlHelper
     * @param Data $_helper
     */
    public function __construct(
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository,
        UrlHelper $urlHelper,
        Data $_helper
    ) {
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper
        );
        $this->_helper = $_helper;
    }

    /**
     * *
     *
     * @return boolean
     */
    public function isEnabledSaleProducts()
    {
        return $this->_helper->isEnabledSaleProducts();
    }

    /**
     * *
     *
     * @param Product $product
     */
    public function getProductPrice($product)
    {
        return $this->_helper->getFormattedPrice(
            $product->getPrice()
        );
    }

    /**
     * *
     *
     * @return array
     */
    public function getSaleProductsCollection()
    {
        $data = [];
        
        $categoryId = $this->_helper->getSaleProductsCategory();
        if ($categoryId) {
            $pageSize = $this->getSaleProductsProductCount();
            $category =  $this->_helper->getCategoryById($categoryId);
            $productCollection = $this->_helper->getProductCollectionByCategories(
                $category,
                $pageSize
            );
            return $productCollection;
        }
        return $data;
    }

    /**
     * *
     */
    public function getCategory()
    {
        $categoryId = $this->_helper->getSaleProductsCategory();
        if ($categoryId) {
            return $this->_helper->getCategoryById($categoryId);
        }
    }

    /**
     * *
     *
     * @param Product $product
     */
    public function getProductImageUrl($product)
    {
        return $this->_helper->getProductImageUrl($product);
    }

    /**
     * *
     *
     * @return string
     */
    public function getSaleProductsProductCount()
    {
        return $this->_helper->getSaleProductsProductCount();
    }
}
