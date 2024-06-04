<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\ViewModel;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
use Exception;
use Magento\Catalog\Model\Product;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\App\State;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Review\Model\Review;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManagerInterface;
use Smartwave\Porto\Helper\Data as DataHelper;
use Smartwave\Porto\Model\Config\Provider;

/**
 * Class view model Data
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data implements ArgumentInterface
{
    /**
     * @var FilterProvider
     */
    protected FilterProvider $filterProvider;

    /**
     * @var Provider
     */
    protected Provider $configProvider;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var DataHelper
     */
    protected DataHelper $dataHelper;

    /**
     * Construct
     *
     * @param StoreManagerInterface $storeManager
     * @param FilterProvider $filterProvider
     * @param Provider $configProvider
     * @param DataHelper $dataHelper
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        FilterProvider $filterProvider,
        Provider $configProvider,
        DataHelper $dataHelper,
    ) {
        $this->storeManager = $storeManager;
        $this->filterProvider = $filterProvider;
        $this->configProvider = $configProvider;
        $this->dataHelper = $dataHelper;
    }

    /**
     * Get Base Url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseUrl(): string
    {
        return $this->dataHelper->getBaseUrl();
    }

    /**
     * Get Base Link Url
     *
     * @return string
     * @throws NoSuchEntityException
     */
    public function getBaseLinkUrl(): string
    {
        return $this->dataHelper->getBaseLinkUrl();
    }

    /**
     * Get Config
     *
     * @param string $configPath
     * @return mixed
     */
    public function getConfig(string $configPath): mixed
    {
        return $this->configProvider->getConfig($configPath);
    }

    /**
     * Get Model
     *
     * @return Review
     */
    public function getModel(): Review
    {
        return $this->dataHelper->getModel();
    }

    /**
     * Get Current Store
     *
     * @return StoreInterface
     * @throws NoSuchEntityException
     */
    public function getCurrentStore(): StoreInterface
    {
        return $this->storeManager->getStore();
    }

    /**
     * Filter Content
     *
     * @param mixed $content
     * @return string
     * @throws Exception
     */
    public function filterContent(mixed $content): string
    {
        return $this->filterProvider->getPageFilter()->filter($content);
    }

    /**
     * Get Category Product Ids
     *
     * @param mixed $currentCategory
     * @return mixed
     */
    public function getCategoryProductIds(mixed $currentCategory): mixed
    {
        return $this->dataHelper->getCategoryProductIds($currentCategory);
    }

    /**
     * Get Prev Product
     *
     * @param mixed $product
     * @return false|Product
     */
    public function getPrevProduct(mixed $product): bool|Product
    {
        return $this->dataHelper->getPrevProduct($product);
    }

    /**
     * Get Next Product
     *
     * @param mixed $product
     * @return false|Product
     */
    public function getNextProduct(mixed $product): bool|Product
    {
        return $this->dataHelper->getNextProduct($product);
    }

    /**
     * Get Masonry Item Class
     *
     * @param array $arr
     * @return string
     */
    public function getMasonryItemClass(array $arr): string
    {
        return $this->dataHelper->getMasonryItemClass($arr);
    }

    /**
     * Get Stock Status
     *
     * @param mixed $product
     * @return bool|int
     */
    public function getStockStatus(mixed $product): bool|int
    {
        return $this->dataHelper->getStockStatus($product);
    }

    /**
     * Get Currency Code
     *
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getCurrencyCode(): ?string
    {
        return $this->getCurrentStore()->getCurrentCurrencyCode();
    }

    /**
     * Return porto helper
     *
     * @return $this
     */
    public function getPortoHelper()
    {
        return $this->dataHelper;
    }
}
