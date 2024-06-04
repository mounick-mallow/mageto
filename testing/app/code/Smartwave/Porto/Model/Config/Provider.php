<?php

namespace Smartwave\Porto\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Provider cet config values
 */
class Provider
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * Construct
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Config
     *
     * @param string $configPath
     * @param mixed|null $storeCode
     * @return mixed
     */
    public function getConfig(
        string $configPath,
        mixed $storeCode = null
    ): mixed {
        return $this->scopeConfig->getValue(
            $configPath,
            ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }
}
