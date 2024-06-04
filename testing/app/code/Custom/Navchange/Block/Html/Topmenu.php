<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * php version 8.1.0
 */
namespace Custom\Navchange\Block\Html;

use Magento\Backend\Model\Menu;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\Node\Collection;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Topmenu extends \Magento\Theme\Block\Html\Topmenu
{
    /**
     * @var array
     */
    protected $identities = [];

    /**
     * @var Node
     */
    protected $_menu;

    /**
     * @var NodeFactory
     */
    private NodeFactory $nodeFactory;

    /**
     * @var TreeFactory
     */
    private TreeFactory $treeFactory;

    /**
     *
     * @param Context $context
     * @param NodeFactory $nodeFactory
     * @param TreeFactory $treeFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        array $data = []
    ) {
        parent::__construct($context, $nodeFactory, $treeFactory, $data);
        $this->nodeFactory = $nodeFactory;
        $this->treeFactory = $treeFactory;
    }

    /**
     * Get block cache life time
     *
     * @return int
     * @since 100.1.0
     */
    protected function getCacheLifetime(): int
    {
        return parent::getCacheLifetime() ?: 3600;
    }

    /**
     * Get top menu html
     *
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     */
    public function getHtml(
        $outermostClass = '',
        $childrenWrapClass = '',
        $limit = 0
    ) {
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_before',
            [
                'menu' => $this->getMenu(),
                'block' => $this,
                'request' => $this->getRequest()
            ]
        );

        $this->getMenu()->setData('quotemost_class', $outermostClass);
        $this->getMenu()->setData('children_wrap_class', $childrenWrapClass);

        $transportObject = new DataObject(
            [
                'html'=> $this->_getHtml(
                    $this->getMenu(),
                    $childrenWrapClass,
                    $limit
                )
            ]
        );

        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_after',
            ['menu' => $this->getMenu(), 'transportObject' => $transportObject]
        );

        return $transportObject->getData('html');
    }

    /**
     * Count All Subnavigation Items
     *
     * @param Menu $items
     *
     * @return int
     */
    protected function _countItems($items): int
    {
        $total = $items->count();
        foreach ($items as $item) {
            /** @var $item Menu\Item */
            if ($item->hasChildren()) {
                $total += $this->_countItems($item->getChildren());
            }
        }
        return $total;
    }

    /**
     * Building Array with Column Brake Stops
     *
     * @param Menu $items
     * @param int $limit
     *
     * @return array|void
     *
     * @todo: Add Depth Level limit, and better logic for columns
     */
    protected function _columnBrake($items, $limit)
    {
        $total = $this->_countItems($items);
        if ($total <= $limit) {
            return;
        }

        $result[] = [
            'total' => $total,
            'max' => (int)ceil($total / ceil($total / $limit))
        ];

        $count = 0;
        $firstCol = true;

        foreach ($items as $item) {
            $place = $this->_countItems($item->getChildren()) + 1;
            $count += $place;

            if ($place >= $limit) {
                $colbrake = !$firstCol;
                $count = 0;
            } elseif ($count >= $limit) {
                $colbrake = !$firstCol;
                $count = $place;
            } else {
                $colbrake = false;
            }

            $result[] = ['place' => $place, 'colbrake' => $colbrake];

            $firstCol = false;
        }

        return $result;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     *
     * @return string HTML code
     */
    protected function _addSubMenu(
        $child,
        $childLevel,
        $childrenWrapClass,
        $limit
    ): string {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $colStops = [];
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getData('children'), $limit);
        }

        $html .= '<ul class="level' . $childLevel . ' ' . $childrenWrapClass . '">';
        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
        $html .= '</ul>';

        return $html;
    }

    /**
     * Get Html
     *
     * @param Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     *
     * @return string
     */
    protected function _getHtml(
        Node $menuTree,
        $childrenWrapClass,
        $limit,
        array $colBrakes = []
    ): string {
        $html = '';
        $children = $menuTree->getChildren();
        $childLevel = $this->getChildLevel($menuTree->getData('level'));
        $this->removeChildrenWithoutActiveParent($children, $childLevel);

        $html .= $this->generateMenuItemsHtml(
            $children,
            $childLevel,
            $colBrakes,
            $limit,
            $menuTree,
            $childrenWrapClass
        );

        if (is_array($colBrakes) && !empty($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }

    /**
     * Generate Menu Items Html
     *
     * @param mixed $children
     * @param int $childLevel
     * @param array $colBrakes
     * @param int $limit
     * @param Node $menuTree
     * @param string $childrenWrapClass
     *
     * @return string
     */
    protected function generateMenuItemsHtml(
        $children,
        $childLevel,
        $colBrakes,
        $limit,
        $menuTree,
        $childrenWrapClass
    ): string {
        $html = '';
        $counter = 1;
        $childrenCount = $children->count();
        $parentPositionClass = $menuTree->getData('position_class');
        $itemPositionClassPrefix = $parentPositionClass
        ? $parentPositionClass . '-'
        : 'nav-';

        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setData('is_first', $counter === 1);
            $child->setData('is_last', $counter === $childrenCount);
            $child->setData('position_class', $itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getData('outermost_class');

            if ($childLevel === 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $this->setCurrentClass($child, $outermostClass);
            }

            $ttip = ($childLevel === 0 && $outermostClass)
                ? '<span class="tooltiptext">Click to View All ' .
                    $this->escapeHtml($child->getName()) . '</span>'
                : '';

            $extra = '';
            if ($childLevel == 1) {
                $extra = '<a href="' . $child->getData('url') . '" class="m2cls">
                        <span style="font-size: 
                        15px !important; 
                        text-transform: 
                        capitalize !important; 
                        border-bottom: none !important; 
                        color: #747070 !important; 
                        margin-left: 16px; 
                        text-decoration: none !important;">
                            All ' . $this->escapeHtml($child->getName()) . '
                        </span>
                    </a>';
            }

            if ($this->shouldAddNewColumn($colBrakes, $counter)) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            $html .= $this->generateMenuItemHtml(
                $child,
                $outermostClassCode,
                $ttip,
                $extra,
                $childLevel,
                $childrenWrapClass,
                $limit
            );
            $counter++;
        }

        return $html;
    }

    /**
     * Generate Menu Item Html
     *
     * @param mixed $child
     * @param string $outermostClassCode
     * @param string $ttip
     * @param string $extra
     * @param int $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     *
     * @return string
     */
    protected function generateMenuItemHtml(
        $child,
        $outermostClassCode,
        $ttip,
        $extra,
        $childLevel,
        $childrenWrapClass,
        $limit
    ): string {
        $menuItemAttributes = $this->_getRenderedMenuItemAttributes($child);
        $submenuHtml = $this->_addSubMenu(
            $child,
            (string)$childLevel,
            $childrenWrapClass,
            $limit
        );

        return '<li ' . $menuItemAttributes . '>
                <a href="' . $child->getData('url') . '" ' . $outermostClassCode . '>
                    <span>' . $this->escapeHtml($child->getName()) . $ttip . '</span>
                </a>' . $extra . $submenuHtml . '</li>';
    }

    /**
     * *
     *
     * Generates string with all attributes that should be present
     * in menu item element
     *
     * @param Node $item
     *
     * @return string
     */
    protected function _getRenderedMenuItemAttributes(Node $item): string
    {
        $html = '';
        $menuItems = $this->_getMenuItemAttributes($item);
        foreach ($menuItems as $attributeName => $attributeValue) {
            $html .= ' ' . $attributeName . '="';
            $html .= str_replace('"', '\"', $attributeValue) . '"';
        }
        return $html;
    }

    /**
     * Returns array of menu item's attributes
     *
     * @param Node $item
     *
     * @return array
     */
    protected function _getMenuItemAttributes(Node $item): array
    {
        return ['class' => implode(' ', $this->_getMenuItemClasses($item))];
    }

    /**
     * Returns array of menu item's classes
     *
     * @param Node $item
     *
     * @return array
     */
    protected function _getMenuItemClasses(Node $item): array
    {
        $classes = [
           'level' . $item->getData('level'),
           $item->getData('position_class'),
        ];

        if ($item->getData('is_category')) {
            $classes[] = 'category-item';
        }

        if ($item->getData('is_first')) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getData('has_active')) {
            $classes[] = 'has-active';
        }

        if ($item->getData('is_last')) {
            $classes[] = 'last';
        }

        if ($item->getData('class')) {
            $classes[] = $item->getData('class');
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        return $classes;
    }

    /**
     * Add identity
     *
     * @param string|array $identity
     *
     * @return void
     */
    public function addIdentity($identity)
    {
        if (!in_array($identity, $this->identities)) {
            $this->identities[] = $identity;
        }
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return $this->identities;
    }

    /**
     * Get tags array for saving cache
     *
     * @return array
     * @since 100.1.0
     */
    protected function getCacheTags(): array
    {
        return array_merge(parent::getCacheTags(), $this->getIdentities());
    }

    /**
     * Get menu object.
     *
     * Creates Tree root node object.
     * The creation logic was moved from class constructor into separate method.
     *
     * @return Node
     * @since 100.1.0
     */
    public function getMenu(): Node
    {
        if (!$this->_menu) {
            $this->_menu = $this->nodeFactory->create(
                [
                   'data' => [],
                   'idField' => 'root',
                   'tree' => $this->treeFactory->create()
                ]
            );
        }
        return $this->_menu;
    }

    /**
     * Remove children from collection when the parent is not active
     *
     * @param Collection $children
     * @param int $childLevel
     *
     * @return void
     */
    private function removeChildrenWithoutActiveParent(
        Collection $children,
        int $childLevel
    ): void {
        /** @var Node $child */
        foreach ($children as $child) {
            if ($childLevel === 0 && $child->getData('is_parent_active') === false) {
                $children->delete($child);
            }
        }
    }

    /**
     * Retrieve child level based on parent level
     *
     * @param int $parentLevel
     *
     * @return int
     */
    private function getChildLevel(int $parentLevel): int
    {
        return $parentLevel ? $parentLevel + 1 : 0;
    }

    /**
     * Check if new column should be added.
     *
     * @param array $colBrakes
     * @param int $counter
     *
     * @return bool
     */
    private function shouldAddNewColumn(array $colBrakes, int $counter): bool
    {
        return count($colBrakes) && $colBrakes[$counter]['colbrake'];
    }

    /**
     * Set current class.
     *
     * @param Node $child
     * @param string $outermostClass
     */
    private function setCurrentClass(Node $child, string $outermostClass): void
    {
        $currentClass = $child->getData('class');
        if (empty($currentClass)) {
            $child->setData('class', $outermostClass);
        } else {
            $child->setData('class', $currentClass . ' ' . $outermostClass);
        }
    }
}
