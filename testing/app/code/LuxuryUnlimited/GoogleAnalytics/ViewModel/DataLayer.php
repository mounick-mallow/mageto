<?php
namespace LuxuryUnlimited\GoogleAnalytics\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class DataLayer implements ArgumentInterface
{
    /**
     * Active flag
     */
    const XML_PATH_ACTIVE = 'luxuryunlimited_google/general/active';

    /**
     * List Events
     */
    const XML_PATH_EVENTS = 'luxuryunlimited_google/general/events';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * Check if module enabled
     *
     * @return bool
     */
    public function isModuleEnabled()
    {
        $active = $this->scopeConfig->isSetFlag(
            self::XML_PATH_ACTIVE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $active;
    }

    /**
     * Get List Events
     *
     * @return string[]
     */
    public function getListEvents()
    {
        $listEvents = $this->scopeConfig->getValue(
            self::XML_PATH_EVENTS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($listEvents) {
            return explode(',', $listEvents);
        } else {
            return [];
        }
    }
}