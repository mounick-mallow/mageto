<?php

namespace Strategery\Infinitescroll\ViewModel;

use Magento\Catalog\Model\Session as CatalogSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Strategery\Infinitescroll\Model\Config\Provider;

class ProductListScroll implements ArgumentInterface
{
    /**
     * @var CatalogSession
     */
    private CatalogSession $catalogSession;

    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Provider
     */
    private Provider $configProvider;

    /**
     * Construct
     *
     * @param CatalogSession $catalogSession
     * @param Registry $registry
     * @param RequestInterface $request
     * @param Provider $configProvider
     */
    public function __construct(
        CatalogSession $catalogSession,
        Registry $registry,
        RequestInterface $request,
        Provider $configProvider
    ) {
        $this->catalogSession = $catalogSession;
        $this->registry = $registry;
        $this->request = $request;
        $this->configProvider = $configProvider;
    }

    /**
     * Function Get Scroll Config
     *
     * @param string $node
     * @return mixed
     */
    public function getScrollConfig(string $node): mixed
    {
        return $this->configProvider->getConfig('strategery_infinitescroll/' . $node);
    }

    /**
     * Function Get Selector
     *
     * @param string $selector
     * @return string|null
     */
    public function getSelector(string $selector): ?string
    {
        return $this->getScrollConfig('selectors/'.$selector);
    }

    /**
     * Function Get Design
     *
     * @param string $design
     * @return string|null
     */
    public function getDesign(string $design): ?string
    {
        return $this->getScrollConfig('design/'.$design);
    }

    /**
     * Function Is Memory Active
     *
     * @return mixed
     */
    public function isMemoryActive(): mixed
    {
        return $this->getScrollConfig('memory/enabled');
    }

    /**
     * Function Is Enabled
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return ($this->getScrollConfig('general/enabled') && $this->isEnabledInCurrentPage());
    }

    /**
     * Function Get Current Page Type
     *
     * @return string|null
     */
    public function getCurrentPageType(): ?string
    {
        $where = 'grid';
        $currentCategory = $this->getCurrentCategory();
        if ($currentCategory) {
            $where = "grid";
            if ($currentCategory->getIsAnchor()) {
                $where = "layer";
            }
        }
        // phpstan:ignore "Call to an undefined method"
        $controller = $this->request->getControllerName();
        if ($controller == "result") {
            $where = "search";
        } elseif ($controller == "advanced") {
            $where = "advanced";
        }
        return $where;
    }

    /**
     * Function Get Current Category
     *
     * @return mixed
     */
    public function getCurrentCategory(): mixed
    {
        return $this->registry->registry('current_category');
    }

    /**
     * Check general and instance enable
     *
     * @return bool
     */
    public function isEnabledInCurrentPage(): bool
    {
        $pageType = $this->getCurrentPageType();
        return $this->getScrollConfig('instances/'.$pageType);
    }

    /**
     * Function Get Product List Mode
     *
     * @return string|null
     */
    public function getProductListMode(): ?string
    {
        // user mode
        $paramProductListMode = $this->request->getParam('product_list_mode');
        $currentMode = $paramProductListMode ?: $this->catalogSession->getDisplayMode();
        if ($currentMode) {
            $productListMode = match ($currentMode) {
                'list' => 'list',
                default => 'grid',
            };
        } else {
            $defaultMode = $this->configProvider->getConfig('catalog/frontend/list_mode');
            $productListMode = match ($defaultMode) {
                'grid-list' => 'grid',
                'list-grid' => 'list',
                default => $defaultMode,
            };
        }
        return $productListMode;
    }
}
