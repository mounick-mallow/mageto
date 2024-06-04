<?php

namespace Smartwave\Megamenu\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Helper\Category;
use Smartwave\Megamenu\Helper\Data;
use Magento\Catalog\Model\Indexer\Category\Flat\State;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Theme\Block\Html\Topmenu as MagentoTopmenu;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Cms\Model\BlockFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Configurable product type implementation
 *
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Topmenu extends Template
{
    /**
     * @var Category
     */
    protected $_categoryHelper;

    /**
     * @var State
     */
    protected $_categoryFlatConfig;

    /**
     * @var MagentoTopmenu
     */
    protected $_topMenu;

    /**
     * @var CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var Data
     */
    protected $_helper;

    /**
     * @var FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var BlockFactory
     */
    protected $_blockFactory;

    /**
     * @var string
     */
    protected $_megamenuConfig;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Constructor
     *
     * @param Template\Context $context
     * @param Category $categoryHelper
     * @param Data $helper
     * @param State $categoryFlatState
     * @param CategoryFactory $categoryFactory
     * @param MagentoTopmenu $topMenu
     * @param FilterProvider $filterProvider
     * @param BlockFactory $blockFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Template\Context $context,
        Category $categoryHelper,
        Data $helper,
        State $categoryFlatState,
        CategoryFactory $categoryFactory,
        MagentoTopmenu $topMenu,
        FilterProvider $filterProvider,
        BlockFactory $blockFactory,
        StoreManagerInterface $storeManager
    ) {

        $this->_categoryHelper = $categoryHelper;
        $this->_categoryFlatConfig = $categoryFlatState;
        $this->_categoryFactory = $categoryFactory;
        $this->_topMenu = $topMenu;
        $this->_helper = $helper;
        $this->_filterProvider = $filterProvider;
        $this->_blockFactory = $blockFactory;
        $this->_storeManager = $storeManager;

        parent::__construct($context);
    }

     /**
      * Return category helper
      *
      * @return Category
      */
    public function getCategoryHelper()
    {
        return $this->_categoryHelper;
    }

     /**
      * Return category object
      *
      * @param mixed $id
      * @return \Magento\Catalog\Model\Category
      */
    public function getCategoryModel($id)
    {
        $_category = $this->_categoryFactory->create();
        $_category->load($id);

        return $_category;
    }

    /**
     * Return html category tree
     *
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return mixed
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {
        return $this->_topMenu->getHtml($outermostClass, $childrenWrapClass, $limit);
    }

    /**
     * Return categories
     *
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @return mixed
     */
    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted, $asCollection, $toLoad);
    }

    /**
     * Return child categories
     *
     * @param \Magento\Catalog\Helper\Category $category
     * @return mixed
     */
    public function getChildCategories($category)
    {
        if ($this->_categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }

        return $subcategories;
    }

    /**
     * Return active child categories
     *
     * @param \Magento\Catalog\Helper\Category $category
     * @return mixed
     */
    public function getActiveChildCategories($category)
    {
        $children = [];
        if ($this->_categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
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
     * Return block html content
     *
     * @param string $type
     * @return mixed
     */
    public function getCustomBlockHtml($type = 'after')
    {
        $html = '';

        $block_ids = $this->_megamenuConfig['custom_links']['staticblock_'.$type];

        if (!$block_ids) {
            return '';
        }

        $block_ids = preg_replace('/\s/', '', $block_ids);
        $ids = explode(',', $block_ids);
        $store_id = $this->_storeManager->getStore()->getId();

        foreach ($ids as $block_id) {
            $block = $this->_blockFactory->create();
            $block->setStoreId($store_id)->load((int) $block_id);

            if (!$block) {
                continue;
            }

            $block_content = $block->getContent();

            if (!$block_content) {
                continue;
            }

            $content = $this->_filterProvider->getBlockFilter()->setStoreId($store_id)->filter($block_content);
            if (substr($content, 0, 4) == '<ul>') {
                $content = substr($content, 4);
            }
            if (substr($content, strlen($content) - 5) == '</ul>') {
                $content = substr($content, 0, -5);
            }

            $html .= $content;
        }

        return $html;
    }

    /**
     * Return sub menu item html content
     *
     * @param mixed $children
     * @param int $level
     * @param int $max_level
     * @param int $column_width
     * @param string $menu_type
     * @param string $columns
     * @return mixed
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function getSubmenuItemsHtml(
        $children,
        $level = 1,
        $max_level = 0,
        $column_width = 12,
        $menu_type = 'fullwidth',
        $columns = null
    ) {
        $html = '';

        if (!$max_level || ($max_level && $max_level == 0)
        || ($max_level && $max_level > 0 && $max_level-1 >= $level)) {
            $column_class = "";
            if ($level == 1 && $columns && ($menu_type == 'fullwidth' || $menu_type == 'staticwidth')) {
                $column_class = "col-md-".$column_width." ";
                $column_class .= "mega-columns columns".$columns;
            }
            $html = '<ul class="subchildmenu '.$column_class.'">';
            foreach ($children as $child) {
                $cat_model = $this->getCategoryModel($child->getId());

                $sw_menu_hide_item = $cat_model->getData('sw_menu_hide_item');

                if (!$sw_menu_hide_item) {
                    $sub_children = $this->getActiveChildCategories($child);

                    $sw_menu_cat_label = $cat_model->getData('sw_menu_cat_label');
                    $sw_menu_icon_img = $cat_model->getData('sw_menu_icon_img');
                    $sw_menu_font_icon = $cat_model->getData('sw_menu_font_icon');

                    $item_class = 'level'.$level.' ';

                    if (!empty($cat_model->getData('menu_additional_class'))) {
                        $item_class .= $cat_model->getData('menu_additional_class') . ' ';
                    }

                    if (count($sub_children) > 0) {
                        $item_class .= 'parent ';
                    }
                    $html .= '<li class="ui-menu-item '.$item_class.'">';
                    if (count($sub_children) > 0) {
                        $html .= '<div class="open-children-toggle"></div>';
                    }
                    if ($level == 1 && $sw_menu_icon_img) {
                        $html .= '<div class="menu-thumb-img"><a class="menu-thumb-link" href="'.
                        $this->_categoryHelper->getCategoryUrl($child).'"><img src="' .
                        $this->_helper->getBaseUrl().'catalog/category/' .
                        $sw_menu_icon_img . '" alt="'.$child->getName().'"/></a></div>';
                    }

                    if (!empty($cat_model->getData('menu_additional_class'))) {
                        $html .= '<a class="mycls nested-link" href="'.$this->_categoryHelper->getCategoryUrl($child);
                    } else {
                        $html .= '<a class="mycls " href="'.$this->_categoryHelper->getCategoryUrl($child);
                    }


                    $html .= '" title="'.$child->getName().'">';
                    if ($level > 1 && $sw_menu_icon_img) {
                        $html .= '<img class="menu-thumb-icon" src="' .
                        $this->_helper->getBaseUrl().'catalog/category/' . $sw_menu_icon_img .
                        '" alt="'.$child->getName().'"/>';
                    } elseif ($sw_menu_font_icon) {
                        $html .= '<em class="menu-thumb-icon '.$sw_menu_font_icon.'"></em>';
                    }
                    $html .= '<span>'.$child->getName();
                    if ($sw_menu_cat_label) {
                        $html .= '<span class="cat-label cat-label-'.$sw_menu_cat_label.'">'.
                        $this->_megamenuConfig['cat_labels'][$sw_menu_cat_label].'</span>';
                    }
                    $html .= '</span></a>';

                    if ($level == 1) {
                        $html .= '<a class="m2cls"  href="'.$this->_categoryHelper->getCategoryUrl($child).
                        '" title="'.$child->getName().'">';
                        $html .= '<span>All '.$child->getName();
                        $html .= '</span></a>';
                    }

                    if (count($sub_children) > 0) {
                        $html .= $this->getSubmenuItemsHtml(
                            $sub_children,
                            $level+1,
                            $max_level,
                            $column_width,
                            $menu_type
                        );
                    }
                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }

        return $html;
    }

    /**
     * Return mega menu html content
     *
     * @return mixed
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getMegamenuHtml()
    {
        $html = '';

        $categories = $this->getStoreCategories(true, false, true);

        $this->_megamenuConfig = $this->_helper->getConfig('sw_megamenu');

        $max_level = $this->_megamenuConfig['general']['max_level'];
        $html .= $this->getCustomBlockHtml('before');


        foreach ($categories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }

            $cat_model = $this->getCategoryModel($category->getId());

            $sw_menu_hide_item = $cat_model->getData('sw_menu_hide_item');

            if (!$sw_menu_hide_item) {
                $children = $this->getActiveChildCategories($category);
                $sw_menu_cat_label = $cat_model->getData('sw_menu_cat_label');
                $sw_menu_icon_img = $cat_model->getData('sw_menu_icon_img');
                $sw_menu_font_icon = $cat_model->getData('sw_menu_font_icon');
                $sw_menu_cat_columns = $cat_model->getData('sw_menu_cat_columns');
                $sw_menu_float_type = $cat_model->getData('sw_menu_float_type');

                if (!$sw_menu_cat_columns) {
                    $sw_menu_cat_columns = 4;
                }

                $menu_type = $cat_model->getData('sw_menu_type');
                if (!$menu_type) {
                    $menu_type = $this->_megamenuConfig['general']['menu_type'];
                }

                $custom_style = '';
                if ($menu_type=="staticwidth") {
                    $custom_style = ' style="width: 500px;"';
                }

                $sw_menu_static_width = $cat_model->getData('sw_menu_static_width');
                if ($menu_type=="staticwidth" && $sw_menu_static_width) {
                    $custom_style = ' style="width: '.$sw_menu_static_width.';"';
                }

                $item_class = 'level0 ';
                if (!empty($cat_model->getData('menu_additional_class'))) {
                    $item_class .=  $cat_model->getData('menu_additional_class') . ' ';
                }

                $item_class .= $menu_type.' ';

                $menu_top_content = $cat_model->getData('sw_menu_block_top_content');
                $menu_left_content = $cat_model->getData('sw_menu_block_left_content');
                $menu_left_width = $cat_model->getData('sw_menu_block_left_width');
                if (!$menu_left_content || !$menu_left_width) {
                    $menu_left_width = 0;
                }
                $menu_right_content = $cat_model->getData('sw_menu_block_right_content');
                $menu_right_width = $cat_model->getData('sw_menu_block_right_width');
                if (!$menu_right_content || !$menu_right_width) {
                    $menu_right_width = 0;
                }
                $menu_bottom_content = $cat_model->getData('sw_menu_block_bottom_content');
                if ($sw_menu_float_type) {
                    $sw_menu_float_type = 'fl-'.$sw_menu_float_type.' ';
                }
                if (count($children) > 0 || (($menu_type=="fullwidth" || $menu_type=="staticwidth") &&
                ($menu_top_content || $menu_left_content || $menu_right_content || $menu_bottom_content))) {
                    $item_class .= 'parent ';
                }
                $html .= '<li class="ui-menu-item '.$item_class.$sw_menu_float_type.'">';
                if (count($children) > 0) {
                    $html .= '<div class="open-children-toggle"></div>';
                }
                $html .= '<a href="'.$this->_categoryHelper->getCategoryUrl($category).
                '" class="level-top" title="'.$category->getName().'">';
                if ($sw_menu_icon_img) {
                    $html .= '<img class="menu-thumb-icon" src="' . $this->_helper->getBaseUrl().'catalog/category/' .
                    $sw_menu_icon_img . '" alt="'.$category->getName().'"/>';
                } elseif ($sw_menu_font_icon) {
                    $html .= '<em class="menu-thumb-icon '.$sw_menu_font_icon.'"></em>';
                }
                $html .= '<span>'.$category->getName().'</span>';
                if ($sw_menu_cat_label) {
                    $html .= '<span class="cat-label cat-label-'.$sw_menu_cat_label.'">'.
                    $this->_megamenuConfig['cat_labels'][$sw_menu_cat_label].'</span>';
                }
                $html .= '</a>';
                if (count($children) > 0 || (($menu_type=="fullwidth" || $menu_type=="staticwidth") &&
                ($menu_top_content || $menu_left_content || $menu_right_content || $menu_bottom_content))) {
                    $html .= '<div class="level0 submenu cat-'.$category->getId().' "'.$custom_style.'>';
                    if (($menu_type=="fullwidth" || $menu_type=="staticwidth")) {
                        $html .= '<div class="container">';
                    }
                    if (($menu_type=="fullwidth" || $menu_type=="staticwidth") && $menu_top_content) {
                        $html .= '<div class="menu-top-block">'.$this->getBlockContent($menu_top_content).'</div>';
                    }
                    if (count($children) > 0 || (($menu_type=="fullwidth" || $menu_type=="staticwidth")
                    && ($menu_left_content || $menu_right_content))) {
                        $html .= '<div class="row">';
                        if (($menu_type=="fullwidth" || $menu_type=="staticwidth") &&
                        $menu_left_content && $menu_left_width > 0) {
                            $html .= '<div class="menu-left-block col-md-'.$menu_left_width.'">'.
                            $this->getBlockContent($menu_left_content).'</div>';
                        }
                        $html .= $this->getSubmenuItemsHtml(
                            $children,
                            1,
                            $max_level,
                            12-$menu_left_width-$menu_right_width,
                            $menu_type,
                            $sw_menu_cat_columns
                        );
                        if (($menu_type=="fullwidth" || $menu_type=="staticwidth") &&
                        $menu_right_content && $menu_right_width > 0) {
                            $html .= '<div class="menu-right-block col-md-'.$menu_right_width.'">'.
                            $this->getBlockContent($menu_right_content).'</div>';
                        }
                        $html .= '</div>';
                    }
                    if (($menu_type=="fullwidth" || $menu_type=="staticwidth") && $menu_bottom_content) {
                        $html .= '<div class="menu-bottom-block">'.
                        $this->getBlockContent($menu_bottom_content).'</div>';
                    }
                    if (($menu_type=="fullwidth" || $menu_type=="staticwidth")) {
                        $html .= '</div>';
                    }
                    $html .= '</div>';
                }
                $html .= '</li>';
            }
        }
        $html .= $this->getCustomBlockHtml('after');

        return $html;
    }
}
