<?php

namespace LuxuryUnlimited\HomeProductSection\Block;
 
// use Magento\Framework\View\Element\Template\Context;
use LuxuryUnlimited\HomeProductSection\Helper\Data;
use Magento\Catalog\Block\Product\Context;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;

class SectionThree extends \Magento\Catalog\Block\Product\ListProduct
{
     
    /**
     * @var Data $helper
     */
    private $_helper;

    /**
     * *
     *
     * @param Context                     $context
     * @param PostHelper                  $postDataHelper
     * @param Resolver                    $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param UrlHelper                   $urlHelper
     * @param Data                        $_helper
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
     * @return string
     */
    public function isEnabledSectionThree()
    {
        return $this->_helper->isEnabledSectionThree();
    }

    /**
     * *
     *
     * @return array
     */
    public function getSectionThreeProductCollection()
    {
        $data = [];
        
        $categoryId = $this->_helper->getSectionThreeCategory();
        if ($categoryId) {
            $pageSize = $this->getSectionThreeProductCount();
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
        $categoryId = $this->_helper->getSectionThreeCategory();
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
     */
    public function getWishListItems()
    {
        $wishListItemIds = [];
        $customerId = $this->_helper->getCustomerId();
        if ($customerId) {
            $wishListItemIds = $this->_helper->getWishListCollectionIds();
        }
        return $wishListItemIds;
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
     * @return string
     */
    public function getSectionThreeProductCount()
    {
        return $this->_helper->getSectionThreeProductCount();
    }
}
