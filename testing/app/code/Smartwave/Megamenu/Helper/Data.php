<?php
/**
 * Copyright © 2018 Porto. All rights reserved.
 */
namespace Smartwave\Megamenu\Helper;

use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Helper\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\Indexer\Category\Flat\State;
use Magento\Framework\ObjectManagerInterface;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var Category
     */
    protected $_categoryHelper;

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var State
     */
    protected $_categoryFlatConfig;

    /**
     * @var FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * Constructor
     *
     * @param Context $context
     * @param Category $categoryHelper
     * @param CategoryFactory $categoryFactory
     * @param State $categoryFlatState
     * @param ObjectManagerInterface $objectManager
     * @param FilterProvider $filterProvider
     * @param PageFactory $resultPageFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        Category $categoryHelper,
        CategoryFactory $categoryFactory,
        State $categoryFlatState,
        ObjectManagerInterface $objectManager,
        FilterProvider $filterProvider,
        PageFactory $resultPageFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        $this->_objectManager= $objectManager;
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryFlatConfig = $categoryFlatState;
        $this->_categoryHelper = $categoryHelper;
        $this->resultPageFactory = $resultPageFactory;
        $this->_filterProvider = $filterProvider;
        
        parent::__construct($context);
    }

    /**
     * Return base url
     *
     * @param string $url_type
     * @return string
     */
    public function getBaseUrl($url_type = UrlInterface::URL_TYPE_MEDIA)
    {
        return $this->_storeManager->getStore()->getBaseUrl($url_type);
    }

    /**
     * Return config value
     *
     * @param string $config_path
     * @return string
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return object
     *
     * @param mixed $model
     * @return object
     */
    public function getModel($model)
    {
        return $this->_objectManager->create($model);
    }

    /**
     * Return store
     *
     * @return object
     */
    public function getCurrentStore()
    {
        return $this->_storeManager->getStore();
    }

    /**
     * Return categories
     *
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @return mixed
     */
    public function getFirstLevelCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted, $asCollection, $toLoad);
    }

    /**
     * Return category model
     *
     * @param int $id
     * @return object
     */
    public function getCategoryModel($id)
    {
        $_category = $this->_categoryFactory->create();
        $_category->load($id);
        
        return $_category;
    }

    /**
     * Return active child categories
     *
     * @param \Magento\Catalog\Model\Category $category
     * @return mixed
     */
    public function getActiveChildCategories($category)
    {
        $children = [];
        $subcategories = $category->getChildrenCategories();
        foreach ($subcategories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }
            $children[] = $category;
        }
        return $children;
    }

     /**
      * Return block content
      *
      * @param string $content
      * @return mixed
      */
    public function getBlockContent($content = '')
    {
        if (!$this->_filterProvider) {
            return $content;
        }
        return $this->_filterProvider->getBlockFilter()->filter(trim($content));
    }

     /**
      * Return page factory
      *
      * @return mixed
      */
    public function getResultPageFactory()
    {
        return $this->resultPageFactory;
    }

    /**
     * Return sub menu item html content
     *
     * @param mixed $children
     * @param int $level
     * @param int $max_level
     * @return mixed
     */
    public function getSubmenuItemsHtml($children, $level = 0, $max_level = 2)
    {
        $html = '';
        if (count($children) && ($level < $max_level)) {
            $html .= '<ul';
            if ($level == 0) {
                $html .=' class="columns5"';
            }
            $html .= '>';
            foreach ($children as $child) {
                $html .= '<li class="menu-item level'.$level;
                $activeChildren = $this->getActiveChildCategories($child);
                
                if (count($activeChildren)) {
                    $html .= ' menu-parent-item';
                }
                $html .= '">';
                
                $html .='<a href="'.$child->getUrl().'" data-id="'.$child->getId().'"><span>'.
                $child->getName().'</span></a>';
                if (count($activeChildren)) {
                    $html .= $this->getSubmenuItemsHtml($activeChildren, $level+1, $max_level);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }
}
