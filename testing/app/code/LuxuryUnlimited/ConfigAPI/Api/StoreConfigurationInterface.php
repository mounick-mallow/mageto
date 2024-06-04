<?php

declare(strict_types=1);

namespace LuxuryUnlimited\ConfigAPI\Api;

/**
 * Interface StoreConfigurationInterface
 */
interface StoreConfigurationInterface
{
    /**
     * Get Store Configuration (using POST request, without url limits) <br><br>
     * You can get store configs to one of scopes (default/stores/websites) <br>
     * If scope is default you can pass scopeId = 0 <br>
     * If scope is stores you should pass storeId value for scopeId field <br>
     * If scope is websites you should pass websiteId value for scopeId field <br>
     *
     * @param \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigGetItemInterface[] $configs   Core config paths
     * @param string                                                       $scopeType ScoptType
     * @param int|null                                                     $scopeId   Scope ID
     *
     * @return \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigDataInterface[]
     */
    public function getConfiguration(array $configs, string $scopeType, ?int $scopeId = null): array;

    /**
     * Set Store Configuration <br><br>
     * You can set store configs to one of scopes (default/stores/websites) <br>
     * If scope is default you can pass scopeId = 0 <br>
     * If scope is stores you should pass storeId value for scopeId field <br>
     * If scope is websites you should pass websiteId value for scopeId field <br>
     *
     * @param \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigItemInterface[] $configs
     * @param int $scopeId
     * @param string $scopeType
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException;
     */
    public function setConfiguration(array $configs, int $scopeId, string $scopeType): string;

    /**
     * Clean Core Config Cache
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function cleanCoreConfigCache(): string;
}
