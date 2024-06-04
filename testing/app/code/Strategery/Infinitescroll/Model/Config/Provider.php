<?php

namespace Strategery\Infinitescroll\Model\Config;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class DataProvider for getting config values
 */
class Provider
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Function Get Config
     *
     * @param string $fullPath
     * @return mixed
     */
    public function getConfig(string $fullPath): mixed
    {
        return $this->scopeConfig->getValue($fullPath, ScopeInterface::SCOPE_STORE);
    }
}
