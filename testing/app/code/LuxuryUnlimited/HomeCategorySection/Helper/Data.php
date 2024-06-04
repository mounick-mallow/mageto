<?php

namespace LuxuryUnlimited\HomeCategorySection\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    public const SECTION_ONE_ENABLED = 'home_page/category_section_one/enable';
    public const SECTION_ONE_CATEGORY_COUNT = 'home_page/category_section_one/category_count';
    public const PLACE_HOLDER = 'catalog/placeholder/thumbnail_placeholder';

    public const SECTION_TWO_ENABLED = 'home_page/category_section_two/enable';
    public const SECTION_TWO_CATEGORY_COUNT = 'home_page/category_section_two/category_count';

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storeManager;

    /**
     * *
     *
     * @param ScopeConfigInterface  $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionOne()
    {
        return $this->getConfigValue(self::SECTION_ONE_ENABLED);
    }

    /**
     * *
     *
     * @return string
     */
    public function getCategorySectionOneCount()
    {
        return $this->getConfigValue(self::SECTION_ONE_CATEGORY_COUNT);
    }
    
    /**
     * *
     *
     * @return string
     */
    public function getPlaceHolderImage()
    {
        $mediaUrl = $this->getMediaUrl();
        $placeHolder = $this->getConfigValue(self::PLACE_HOLDER);
        return ($placeHolder)
            ? $mediaUrl.'catalog/product/placeholder/'.$placeHolder
            : '';
    }

    /**
     * *
     *
     * @param string $path
     *
     * @return string
     */
    public function getConfigValue($path)
    {
        $storeId = $this->getCurrentStoreId();
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * *
     *
     * @return string
     */
    public function getCurrentStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }

    /**
     * *
     *
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    
    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionTwo()
    {
        return $this->getConfigValue(self::SECTION_TWO_ENABLED);
    }

    /**
     * *
     *
     * @return string
     */
    public function getCategorySectionTwoCount()
    {
        return $this->getConfigValue(self::SECTION_TWO_CATEGORY_COUNT);
    }
}
