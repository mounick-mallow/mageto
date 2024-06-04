<?php
namespace LuxuryUnlimited\HomeCategorySection\Block;

use Magento\Framework\View\Element\Template\Context;
use LuxuryUnlimited\HomeCategorySection\Helper\Data;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as Category;

class SectionTwo extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Category $categoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var Category $categoryCollectionFactory
     */
    private $_helper;
 
    /**
     * *
     *
     * @param Context $context
     * @param Category $categoryCollectionFactory
     * @param Data $_helper
     */
    public function __construct(
        Context $context,
        Category $categoryCollectionFactory,
        Data $_helper
    ) {
        parent::__construct($context);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->_helper =  $_helper;
    }

    /**
     * *
     *
     * @return Category $categoryCollectionFactory
     */
    public function getSectionTwoCategories()
    {
        $fields = ['name','section_two_image'];
        $count = $this->_helper->getCategorySectionTwoCount();
        $categories = $this->categoryCollectionFactory->create()
            ->addFieldToSelect($fields)
            ->addFieldToFilter('section_two_display', 1)
            ->setStore($this->_storeManager->getStore())
            ->setCurPage(1);

        if ($count) {

            $categories->setPageSize($count);
        }
        $categoryOut = [];
        foreach ($categories as $category) {
            $image = ($category->getSectionTwoImage())
                ? $category->getSectionTwoImage()
                : $this->_helper->getPlaceHolderImage();
            $categoryData =
            [
                'name' => $category->getName(),
                'image' => $image,
                'url' => $category->getUrl()
            ];
            $categoryOut[] = $categoryData;
        }
        return $categoryOut;
    }

    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionTwo()
    {
        return $this->_helper->isEnabledSectionTwo();
    }
}
