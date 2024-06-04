<?php
namespace Smartwave\Porto\ViewModel;

use Magento\Cms\Model\BlockFactory;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smartwave\Porto\Model\Config\Provider;

class Customtabs implements ArgumentInterface
{
    /**
     * @var FilterProvider
     */
    protected $filterProvider;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var BlockFactory
     */
    protected $blockFactory;

    /**
     * @var Provider
     */
    private Provider $configProvider;

    /**
     * Construct
     *
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param BlockFactory $blockFactory
     * @param Provider $configProvider
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        BlockFactory $blockFactory,
        Provider $configProvider,
    ) {
        $this->filterProvider = $filterProvider;
        $this->blockFactory = $blockFactory;
        $this->storeManager = $storeManager;
        $this->configProvider = $configProvider;
    }

    /**
     * Get Custom Tabs
     *
     * @param mixed $product
     * @return array
     * @throws NoSuchEntityException
     *
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function getCustomTabs($product)
    {
        $cmsTabs = $this->configProvider->getConfig(
            'porto_settings/product/custom_cms_tabs'
        );
        $attrTabs = $this->configProvider->getConfig(
            'porto_settings/product/custom_attr_tabs'
        );
        $sku = $product->getSku();
        // phpcs:disable
        if ($cmsTabs) {
            $cmsTabs = unserialize($cmsTabs);
        }
        if ($attrTabs) {
            $attrTabs = unserialize($attrTabs);
        }
        // phpcs:enable
        
        $parents = [];
        if (count($cmsTabs)>0 || count($attrTabs)>0) {
            foreach ($product->getCategoryCollection() as $parentCat) {
                $parents[] = $parentCat->getId();
            }
        }
        $storeId = $this->storeManager->getStore()->getId();
        $customTabs = [];
        if (count($cmsTabs)>0) {
            foreach ($cmsTabs as $item) {
                if ($this->checkShowingTab(
                    $item['category_ids'],
                    $parents,
                    $item['product_skus'],
                    $sku
                )
                ) {
                    $blockId = $item['staticblock_id'];
                    if (!$blockId) {
                        continue;
                    }
                    $block = $this->blockFactory->create();
                    $block->setStoreId($storeId)->load($blockId);

                    $blockContent = $block->getContent();
                            
                    $content = $this->getContent($storeId, $blockContent);
                    $arr = [];
                    $arr['tab_title'] = $item['tab_title'];
                    $arr['tab_content'] = $content;
                    $arr['sort_order'] = (
                        !$item['sort_order']
                        || !is_numeric($item['sort_order'])
                    ) ? 0 : $item['sort_order'];
                    $customTabs[] = $arr;
                }
            }
        }
        if (count($attrTabs)>0) {
            foreach ($attrTabs as $item) {
                if ($this->checkShowingTab(
                    $item['category_ids'],
                    $parents,
                    $item['product_skus'],
                    $sku
                )) {
                    $attrCode = $item['attribute_code'];
                    
                    $attribute = $product->getResource()->getAttribute($attrCode);
                    if (!$attribute) {
                        continue;
                    }
                    $attrValue = $attribute->getFrontend()->getValue($product);
                    if (!$attrValue) {
                        continue;
                    }
                    
                    $content = $this->getContent($storeId, $attrValue);
                    $arr = [];
                    $arr['tab_title'] = $item['tab_title'];
                    $arr['tab_content'] = $content;
                    $arr['sort_order'] = (
                        !$item['sort_order']
                        || !is_numeric($item['sort_order'])
                    ) ? 0 : $item['sort_order'];
                    $customTabs[] = $arr;
                }
            }
        }
        if (count($customTabs)>0) {
            $customTabs = $this->subvalSort($customTabs);
        }
        
        return $customTabs;
    }

    /**
     * Function subvalSort()
     *
     * @param array $a
     * @return array
     */
    private function subvalSort(array $a): array
    {
        $b = [];
        $c = [];
        foreach ($a as $k => $v) {
            $b[$k] = strtolower($v['sort_order']);
        }
        asort($b);
        foreach ($b as $key => $val) {
            $c[] = $a[$key];
        }
        return $c;
    }

    /**
     * Check ShowingTab
     *
     * @param string $tabCatIds
     * @param array $parentCatIds
     * @param string $tabProdSkus
     * @param string $prodSku
     * @return bool
     */
    private function checkShowingTab(
        string $tabCatIds,
        array $parentCatIds,
        string $tabProdSkus,
        string $prodSku
    ) {
        if (!$tabCatIds && !$tabProdSkus) {
            return true;
        }
        $tabCatIds = explode(",", $tabCatIds);
        $tabProdSkus = explode(",", $tabProdSkus);

        if (in_array($prodSku, $tabProdSkus) ||
            count(array_intersect($tabCatIds, $parentCatIds))>0) {
            return true;
        }

        if (in_array($prodSku, $tabProdSkus)) {
            return true;
        }

        if (count(array_intersect($tabCatIds, $parentCatIds))>0) {
            return true;
        }

        return false;
    }

    /**
     * Get Content
     *
     * @param int $storeId
     * @param string $blockContent
     * @return string
     */
    private function getContent(int $storeId, string $blockContent): string
    {
        return $this->filterProvider->getBlockFilter()->setStoreId(
            $storeId
        )->filter($blockContent);
    }
}
