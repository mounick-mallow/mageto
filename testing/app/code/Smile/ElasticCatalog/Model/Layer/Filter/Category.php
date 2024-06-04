<?php

declare(strict_types=1);

namespace Smile\ElasticCatalog\Model\Layer\Filter;

use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Filter\DataProvider\Category as FilterCategoryDataProvider;
use Magento\Catalog\Model\Layer\Filter\DataProvider\CategoryFactory;
use Magento\Catalog\Model\Layer\Filter\Item\DataBuilder;
use Magento\Catalog\Model\Layer\Filter\ItemFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filter\StripTags;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Smile\ElasticsuiteCatalog\Model\Layer\Filter\Category as OriginalCategory;
use Smile\ElasticsuiteCatalog\Model\Search\Request\Field\Mapper as RequestFieldMapper;
use Smile\ElasticsuiteCore\Api\Search\ContextInterface;

/**
 * Class Category
 */
class Category extends OriginalCategory
{
    /**
     * Filter category data provider
     *
     * @var FilterCategoryDataProvider
     */
    private $dataProvider;

    /**
     * Search context
     *
     * @var ContextInterface
     */
    private $searchContext;

    /**
     * Category collection factory
     *
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * Strip tags
     *
     * @var StripTags
     */
    private $tagFilter;

    /**
     * Current filter value
     *
     * @var null
     */
    private $currentFilterValue;

    /**
     * Current filter label
     *
     * @var null
     */
    private $currentFilterLabel;

    /**
     * Category constructor.
     *
     * @param ItemFactory $filterItemFactory
     * @param StoreManagerInterface $storeManager
     * @param Layer $layer
     * @param DataBuilder $itemDataBuilder
     * @param Escaper $escaper
     * @param CategoryFactory $dataProviderFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param ContextInterface $context
     * @param RequestFieldMapper $requestFieldMapper
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param StripTags $tagFilter
     * @param boolean $useUrlRewrites
     * @param array $data
     */
    public function __construct(
        ItemFactory $filterItemFactory,
        StoreManagerInterface $storeManager,
        Layer $layer,
        DataBuilder $itemDataBuilder,
        Escaper $escaper,
        CategoryFactory $dataProviderFactory,
        ScopeConfigInterface $scopeConfig,
        ContextInterface $context,
        RequestFieldMapper $requestFieldMapper,
        CategoryCollectionFactory $categoryCollectionFactory,
        StripTags $tagFilter,
        $useUrlRewrites = false,
        array $data = []
    ) {
        parent::__construct(
            $filterItemFactory,
            $storeManager,
            $layer,
            $itemDataBuilder,
            $escaper,
            $dataProviderFactory,
            $scopeConfig,
            $context,
            $requestFieldMapper,
            $useUrlRewrites,
            $data
        );
        $this->dataProvider   = $dataProviderFactory->create(['layer' => $this->getLayer()]);
        $this->searchContext  = $context;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->tagFilter = $tagFilter;
    }

    /**
     * Apply filter
     *
     * @param RequestInterface $request
     * @return OriginalCategory
     * @throws LocalizedException
     */
    public function apply(RequestInterface $request): OriginalCategory
    {
        $categoryId = $request->getParam('id');
        $categoryIds = $request->getParam($this->_requestVar);
        $categoryIds = $categoryIds && !is_array($categoryIds) ? [$categoryIds] : $categoryIds;


        if (!empty($categoryId)) {
            $this->dataProvider->setCategoryId($categoryId);

            $category = $this->dataProvider->getCategory();

            $this->searchContext->setCurrentCategory($category);

            $this->currentFilterValue = [];
            $this->currentFilterLabel = [];
            if ($categoryIds) {
                $this->currentFilterValue = array_values($categoryIds);
                $collection = $this->categoryCollectionFactory->create();
                $collection->addAttributeToSelect('name');
                $collection->addFieldToFilter('entity_id', ['in' => $categoryIds]);
                $ids = [];

                foreach ($collection as $item) {
                    $ids = array_merge($ids, $item->getAllChildren(true));
                    $this->currentFilterLabel[$item->getId()] = $item->getName();
                }

                $this->getLayer()->getProductCollection()->addCategoriesFilter(['in' => $ids]);
            } else {
                $this->applyCategoryFilterToCollection($category);
            }


            if ($request->getParam('id') != $category->getId() && $this->dataProvider->isValid()) {
                $this->getLayer()->getState()->addFilter($this->_createItem($category->getName(), $categoryId));
            }

            $layerState = $this->getLayer()->getState();
            foreach ($this->currentFilterValue as $currentFilter) {
                $label = $this->currentFilterLabel[$currentFilter] ?? $currentFilter;
                $values = array_diff($this->currentFilterValue, [$currentFilter]);
                $filter = $this->_createItem($this->tagFilter->filter($label), $values);
                $layerState->addFilter($filter);
            }
        }

        return $this;
    }

    /**
     * Initialize items
     *
     * @return OriginalCategory
     */
    protected function _initItems(): OriginalCategory
    {
        $data = $this->_getItemsData();
        $items = [];

        foreach ($data as $itemData) {
            $item = $this->_createItem($itemData['label'], $itemData['value'], $itemData['count']);

            $applyValue = $itemData['value'];
            if (($valuePos = array_search($applyValue, $this->currentFilterValue)) !== false) {
                $item->setIsSelected(true);
                $applyValue = $this->currentFilterValue;
                unset($applyValue[$valuePos]);
            } else {
                $applyValue = array_merge($this->currentFilterValue, [$itemData['value']]);
            }
            $item->setApplyFilterValue(array_values($applyValue));

            $items[] = $item;

            if ($this->useUrlRewrites() === true) {
                $item->setUrlRewrite($itemData['url']);
            }


        }
        $this->_items = $items;

        return $this;
    }
}
