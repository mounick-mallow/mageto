<?php

namespace LuxuryUnlimited\HomeProductSection\Block;

// use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Block\Product\Context;
use LuxuryUnlimited\HomeProductSection\Helper\Data;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Framework\Url\Helper\Data as UrlHelper;

class SectionOne extends \Magento\Catalog\Block\Product\ListProduct
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
    public function isEnabledSectionOne()
    {
        return $this->_helper->isEnabledSectionOne();
    }

    /**
     * *
     *
     * @return array
     */
    public function getSectionOneProductCollection()
    {
        $data = [];

        $categoryId = $this->_helper->getSectionOneCategory();
        if ($categoryId) {
            $pageSize = $this->getSectionOneProductCount();
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
        $categoryId = $this->_helper->getSectionOneCategory();
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
     * @param Product $collection
     */
    public function getSimpleData($collection)
    {
        $mediaUrl = $this->_helper->getMediaUrl();
        $image = $this->_helper->getMediaGalleryImageUrl($collection);
            $item['image'] =  ($image)
            ? $mediaUrl.'catalog/product'.$image
            : $this->_helper->getPlaceHolderImage();

            $item['price'] =  $this->_helper->getFormattedPrice(
                $collection->getPrice()
            );

            $specialPrice = $collection->getSpecialPrice($collection);
            $item['special_price'] = ($specialPrice)
            ? $this->_helper->getFormattedPrice($specialPrice)
            : '';
        return $item;
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
    public function getSectionOneProductCount()
    {
        return $this->_helper->getSectionOneProductCount();
    }
}
