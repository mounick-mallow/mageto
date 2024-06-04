<?php
/**
 * Meetanshi_DeferJS
 *
 * @copyright   Copyright (c) 2023 IdeaInYou
 * @author      RuslanP <ruslan.p@ideainyou.com>
 */

namespace Meetanshi\DeferJS\Model\Config;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class DataProvider for getting config value
 */
class Provider
{
    private const IS_ENABLED = 'deferjs/general/active';

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
     * Function isEnabled()
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        $active = $this->scopeConfig->getValue(
            self::IS_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
        if ($active) {
            return true;
        }
        return false;
    }
}
