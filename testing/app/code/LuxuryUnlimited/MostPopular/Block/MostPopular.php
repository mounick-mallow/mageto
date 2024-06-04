<?php

namespace LuxuryUnlimited\MostPopular\Block;

// use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use LuxuryUnlimited\MostPopular\Helper\Data;

class MostPopular extends \Magento\Catalog\Block\Product\ListProduct
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
    public function isEnabledMostPopular()
    {
        return $this->_helper->isEnabledMostPopular();
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
    public function getMostPopularProductCollection()
    {
        $data = [];
        
        $categoryId = $this->_helper->getMostPopularCategory();
        if ($categoryId) {
            $pageSize = $this->getMostPopularProductCount();
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
        $categoryId = $this->_helper->getMostPopularCategory();
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
    public function getMostPopularProductCount()
    {
        return $this->_helper->getMostPopularProductCount();
    }
}
