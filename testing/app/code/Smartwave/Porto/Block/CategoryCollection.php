<?php

namespace Smartwave\Porto\Block;

use Magento\Catalog\Helper\Category;
use Magento\Catalog\Model\Indexer\Category\Flat\State;
use Magento\Framework\Data\Tree\Node\Collection;
use Magento\Framework\View\Element\Template as ViewTemplate;
use Magento\Framework\View\Element\Template\Context;
use Magento\Theme\Block\Html\Topmenu;

/**
 * Class block get Category Collection
 */
class CategoryCollection extends ViewTemplate
{
    /**
     * @var Category
     */
    protected Category $categoryHelper;

    /**
     * @var State
     */
    protected State $categoryFlatConfig;

    /**
     * @var Topmenu
     */
    protected Topmenu $topMenu;

    /**
     * Construct
     *
     * @param Context $context
     * @param Category $categoryHelper
     * @param State $categoryFlatState
     * @param Topmenu $topMenu
     */
    public function __construct(
        Context $context,
        Category $categoryHelper,
        State $categoryFlatState,
        Topmenu $topMenu
    ) {
        $this->categoryHelper = $categoryHelper;
        $this->categoryFlatConfig = $categoryFlatState;
        $this->topMenu = $topMenu;
        parent::__construct($context);
    }

    /**
     * Return categories helper
     *
     * @return Category
     */
    public function getCategoryHelper(): Category
    {
        return $this->categoryHelper;
    }

    /**
     * Return categories helper
     *
     * @return string
     */
    public function getHtml(): string
    {
        return $this->topMenu->getHtml();
    }

    /**
     * Retrieve current store categories
     *
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @return array|Collection
     */
    public function getStoreCategories(
        bool $sorted = false,
        bool $asCollection = false,
        bool $toLoad = true
    ): Collection|array {
        return $this->categoryHelper->getStoreCategories(
            $sorted,
            $asCollection,
            $toLoad
        );
    }

    /**
     * Retrieve child store categories
     *
     * @param mixed $category
     * @return array
     */
    public function getChildCategories($category): array
    {
        if ($this->categoryFlatConfig->isFlatEnabled() &&
        $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }

        return $subcategories;
    }

    /**
     * Get Child Category Html
     *
     * @param mixed $category
     * @param string $iconOpenClass
     * @param string $iconCloseClass
     * @return string
     */
    public function getChildCategoryHtml(
        mixed $category,
        string $iconOpenClass = "porto-icon-plus-squared",
        string $iconCloseClass = "porto-icon-minus-squared"
    ): string {
        $html = '';
        if ($childrenCategories = $this->getChildCategories($category)) {
            $html .= '<ul>';
            $i = 0;
            foreach ($childrenCategories as $childrenCategory) {
                if (!$childrenCategory->getIsActive()) {
                    continue;
                }
                $i++;
                $html .= '<li><a href="' .
                $this->categoryHelper->getCategoryUrl($childrenCategory)
                    . '">' . $childrenCategory->getName() . '</a>';
                $html .= $this->getChildCategoryHtml(
                    $childrenCategory,
                    $iconOpenClass,
                    $iconCloseClass
                );
                $html .= '</li>';
            }
            $html .= '</ul>';
            if ($i > 0) {
                $html .= '<a href="javascript:void(0)" ';
                $html .= 'class="expand-icon"><em class="'.
                $iconOpenClass.'"></em></a>';
            }
        }

        return $html;
    }

    /**
     * Get Category Sidebar Html
     *
     * @param string $iconOpenClass
     * @param string $iconCloseClass
     * @return string
     */
    public function getCategorySidebarHtml(
        string $iconOpenClass = "porto-icon-plus-squared",
        string $iconCloseClass = "porto-icon-minus-squared"
    ): string {
        $html = '';
        $categories = $this->getStoreCategories(true, false, true);
        $html .= '<ul class="category-sidebar">';
        foreach ($categories as $category) {
            if (!$category->getIsActive()) {
                continue;
            }
            $html .= '<li>';
            $html .= '<a href="'.
            $this->categoryHelper->getCategoryUrl($category) . '">'
                                . $category->getName() . '</a>';
            $html .= $this->getChildCategoryHtml(
                $category,
                $iconOpenClass,
                $iconCloseClass
            );
            $html .= '</li>';
        }
        $html .= '</ul>';
        $html .= '<script type="text/javascript">
            require([
                \'jquery\'
              ], function ($) {
                $(".category-sidebar li > .expand-icon") . click(function(){
                    if($(this) . parent().hasClass("opened")){
                        $(this) . parent() . children("ul").slideUp();
                        $(this) . parent() . removeClass("opened");
                        $(this) . children(".' . $iconCloseClass . '") .
                        removeClass("'
                                . $iconCloseClass.'") . addClass("' .
                                $iconOpenClass . '");
                    } else {
                        $(this) . parent() . children("ul") . slideDown();
                        $(this) . parent() . addClass("opened");
                        $(this) .children(".' . $iconOpenClass .
                        '") . removeClass("'
                                . $iconOpenClass . '") . addClass("' .
                                $iconCloseClass . '");
                    }
                });
            });
        </script>';

        return $html;
    }
}
