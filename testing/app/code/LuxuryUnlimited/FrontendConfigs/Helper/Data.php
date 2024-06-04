<?php
namespace LuxuryUnlimited\FrontendConfigs\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Get store configuration value
     *
     * @param string $path
     *
     * @return string
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }
}
