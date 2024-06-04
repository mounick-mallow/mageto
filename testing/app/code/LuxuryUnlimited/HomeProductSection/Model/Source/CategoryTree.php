<?php declare(strict_types=1);

namespace LuxuryUnlimited\HomeProductSection\Model\Source;

use Magento\Catalog\Api\Data\CategoryTreeInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\CategoryManagement;

class CategoryTree implements OptionSourceInterface
{
    /**
     * @param StoreManagerInterface $storeManager
     * @param CategoryManagement $categoryManagement
     */
    public function __construct(
        private StoreManagerInterface $storeManager,
        private CategoryManagement $categoryManagement
    ) {
    }

    /**
     * Category list options
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function toOptionArray(): array
    {
        $rootCategoryId = $this->storeManager->getStore()->getRootCategoryId();
        $categoryTree = $this->categoryManagement->getTree($rootCategoryId);

        if (!$categoryTree || !$categoryTree->getChildrenData()) {
            return [];
        }

        $options = [];
        $this->getPreparedOptions($categoryTree, $options);
        array_shift($options);
        return $options;
    }

    /**
     * Prepare options
     *
     * @param CategoryTreeInterface $categoryTree
     * @param array $options
     * @return void
     */
    private function getPreparedOptions(CategoryTreeInterface $categoryTree, array &$options): void
    {
        $label = '&#9656;' . $categoryTree->getName();
        if ($categoryTree->getLevel() > 2) {
            $indent = $this->getIndent($categoryTree->getLevel());
            $label = $indent . $categoryTree->getName();
        }
        $options[] = [
            'label' => $label,
            'value' => $categoryTree->getId()
        ];

        if ($categoryTree->getChildrenData()) {
            foreach ($categoryTree->getChildrenData() as $category) {
                $this->getPreparedOptions($category, $options);
            }
        }
    }

    /**
     * Get indent
     *
     * @param int $level
     * @return string
     */
    private function getIndent($level)
    {
        if ($level > 3) {
            $level *= 2;
        }

        $indent = '';
        for ($i = 0; $i < $level; $i++) {
              $indent .= ' &nbsp;';
        }
        return $indent . ' &#9656; ';
    }
}
