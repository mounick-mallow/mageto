<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Model\Import;

use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Config\ConfigResource\ConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Xml\Parser;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Demo get import
 */
class Demo
{
    public const IMPORT_PATH = BP . // @phpstan-ignore-line
        '/app/code/Smartwave/Porto/etc/import/';

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;

    /**
     * @var StoreManagerInterface
     */
    protected StoreManagerInterface $storeManager;

    /**
     * @var Parser
     */
    protected Parser $parser;

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $configFactory;

    /**
     * @var ObjectManagerInterface
     */
    protected ObjectManagerInterface $objectManager;

    /**
     * @var TypeListInterface
     */
    protected TypeListInterface $cacheTypeList;

    /**
     * @var File
     */
    private File $driver;

    /**
     * Construct
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param ObjectManagerInterface $objectManager
     * @param ConfigInterface $configFactory
     * @param TypeListInterface $cacheTypeList
     * @param File $driver
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        ObjectManagerInterface $objectManager,
        ConfigInterface $configFactory,
        TypeListInterface $cacheTypeList,
        File $driver
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->configFactory = $configFactory;
        $this->objectManager= $objectManager;
        $this->cacheTypeList = $cacheTypeList;
        $this->parser = new Parser();
        $this->driver = $driver;
    }

    /**
     * Import Demo
     *
     * @param mixed $demo_version
     * @param mixed|null $store
     * @param mixed|null $website
     * @return DataObject
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * // phpcs:disable
     */
    public function importDemo(mixed $demo_version, mixed $store = null, mixed $website = null): DataObject
    {
        // Default response
        $gatewayResponse = new DataObject([
            'is_valid' => false,
            'import_path' => '',
            'request_success' => false,
            'request_message' => __('Error during Import '.$demo_version.'.'),
        ]);

        try {
            $xmlPath = self::IMPORT_PATH . $demo_version . '.xml';

            if (!$this->driver->isReadable($xmlPath)) {
                throw new LocalizedException(
                    __("Can't get the data file for import ".$demo_version.": ".$xmlPath)
                );
            }
            $data = $this->parser->load($xmlPath)->xmlToArray();
            $scope = "default";
            $scope_id = 0;
            if ($store && $store > 0) {
                $scope = "stores";
                $scope_id = $store;
            } elseif ($website && $website > 0) {
                $scope = "websites";
                $scope_id = $website;
            }
            foreach ($data['root']['config'] as $b_name => $b) {
                foreach ($b as $c_name => $c) {
                    foreach ($c as $d_name => $d) {
                        $this->configFactory->saveConfig($b_name.'/'.$c_name.'/'.$d_name, $d, $scope, $scope_id);
                    }
                }
            }

            $this->cacheTypeList->cleanType(Config::TYPE_IDENTIFIER);

            $gatewayResponse->setData('is_valid', true);
            $gatewayResponse->setData('request_success', true);

            if ($gatewayResponse->getData('is_valid')) {
                $gatewayResponse->setData('request_message', __('Success to Import ' . $demo_version . '.'));
            } else {
                $gatewayResponse->setData('request_message', __('Error during Import '.$demo_version.'.'));
            }
        } catch (\Exception $exception) {
            $gatewayResponse->setData('is_valid', false);
            $gatewayResponse->setData('request_message', $exception->getMessage());
        }

        return $gatewayResponse;
    }
}
