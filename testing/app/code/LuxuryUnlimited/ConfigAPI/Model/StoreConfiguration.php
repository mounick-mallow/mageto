<?php

declare(strict_types=1);

namespace LuxuryUnlimited\ConfigAPI\Model;

use LuxuryUnlimited\ConfigAPI\Api\StoreConfigurationInterface;
use LuxuryUnlimited\ConfigAPI\Model\Data\ConfigData;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use LuxuryUnlimited\ConfigAPI\Api\Data\ConfigItemInterface;
use LuxuryUnlimited\ConfigAPI\Api\Data\ConfigGetItemInterface;

/**
 * Set StoreConfiguration Class
 * Class StoreConfiguration
 */
class StoreConfiguration implements StoreConfigurationInterface
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public $scopeConfig;
    /**
     * @var \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     */
    public $configWriter;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface $encryptor
     */

    private $encryptor;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */

    private $storeManager;
     
    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface    $scopeConfig
     * @param \Magento\Framework\App\Config\Storage\WriterInterface $configWriter
     * @param \Magento\Framework\Encryption\EncryptorInterface      $encryptor
     * @param \Magento\Store\Model\StoreManagerInterface            $storeManager
     */

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WriterInterface $configWriter,
        EncryptorInterface $encryptor,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->configWriter = $configWriter;
        $this->encryptor = $encryptor;
        $this->storeManager = $storeManager;
    }

    /**
     * Get Store Configuration (using POST request, without url limits)
     *
     * @param \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigGetItemInterface[]  $configs
     * @param string                                                        $scopeType
     * @param int|null                                                      $scopeId
     *
     * @return \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigDataInterface[]
     */
    public function getConfiguration(array $configs, string $scopeType, ?int $scopeId = null): array
    {
        if ($scopeId === null) {
            $scopeId = (int)$this->storeManager->getStore()->getId();
        }
        $scope = $this->checkScopeId($scopeType, $scopeId);

        $results = [];
        foreach ($this->getConfigurationValues($configs, $scopeId, $scope) as $config) {
            $result = new ConfigData();
            $result->setPath($config['path']);
            $result->setValue($config['value']);
            $results[] = $result;
        }

        return $results;
    }

    /**
     * Set Store Configuration
     *
     * @param \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigItemInterface[] $configs
     * @param int                                                       $scopeId
     * @param string                                                    $scopeType
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function setConfiguration(array $configs, int $scopeId, string $scopeType): string
    {
        $scope = $this->checkScopeId($scopeType, $scopeId);
        foreach ($configs as $config) {
            $config = $config->toArray();
            if (isset($config['path']) && array_key_exists('value', $config)) {

                try {
                    $value = !empty($config['encrypt']) ?
                    $this->encryptor->encrypt($config['value']) : $config['value'];

                    if ($scope === ScopeConfigInterface::SCOPE_TYPE_DEFAULT) {
                        $this->configWriter->save($config['path'], $value);
                    } else {
                        $this->configWriter->save(
                            $config['path'],
                            $value,
                            $scope,
                            $scopeId
                        );
                    }
                } catch (LocalizedException $e) {
                    throw new LocalizedException(
                        __('Something went wrong while saving config data.')
                    );
                     
                }
            } else {
                throw new LocalizedException(__('Invalid Request'));
            }
        }

        return 'Success';
    }

    /**
     * Clean Core Config Cache
     *
     * @return string
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function cleanCoreConfigCache(): string
    {
        try {
            $this->scopeConfig->clean();
        } catch (\Exception $e) {
            throw new LocalizedException(
                __('Something went wrong while cleaning Core Config Cache. Exception: %1', $e->getMessage())
            );
        }

        return 'Core Config Cache is cleared successfully.';
    }

    /**
     * Set GetConfigurationValues
     *
     * @param \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigItemInterface[] $configs
     * @param int|null                                                  $scopeId
     * @param string                                                    $scopeType
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getConfigurationValues(array $configs, ?int $scopeId, string $scopeType): array
    {
        try {
            $result = [];

            foreach ($configs as $index => $path) {
                $path = $path->getPath();
                $value = $this->scopeConfig->getValue($path, $scopeType, $scopeId);

                if (is_string($value) && !mb_detect_encoding($value, 'UTF-8', true)) {
                    $value = utf8_encode($value);
                }

                $result[$index]['path'] = $path;
                $result[$index]['value'] = $value;
            }

            return $result;
        } catch (\Exception $e) {
            throw new LocalizedException(__('Something went wrong while fetching config data.'));
        }
    }

    /**
     * Check Out Scope
     *
     * @param string $scopeType
     * @param int|null $scopeId
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function checkScopeId($scopeType, $scopeId): string
    {
        switch ($scopeType) {
            case "default":
                $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;
                break;
            case "stores":
                try {
                    $this->storeManager->getStore($scopeId);
                } catch (LocalizedException $e) {
                    throw new LocalizedException(__('No store with specified Id'));
                }
                $scope = ScopeInterface::SCOPE_STORES;
                break;
            case "websites":
                try {
                    $this->storeManager->getWebsite($scopeId);
                } catch (\Exception $e) {
                    throw new LocalizedException(__('No website with specified Id'));
                }
                $scope = ScopeInterface::SCOPE_WEBSITES;
                break;
            default:
                throw new LocalizedException(__('Unknown scope type'));
        }

        return $scope;
    }
}
