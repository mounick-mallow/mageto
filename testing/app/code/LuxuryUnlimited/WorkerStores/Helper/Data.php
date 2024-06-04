<?php
/**
 * LuxuryUnlimited_WorkerStores
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\WorkerStores\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use LuxuryUnlimited\WorkerStores\Model\ResourceModel\WorkerStores;
use \Magento\Framework\App\Helper\AbstractHelper;
/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Data extends AbstractHelper
{

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @var WorkerStores
     */
    protected $workerStore;

    /**
     * Data constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param WorkerStores $workerStore
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        WorkerStores $workerStore
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->workerStore = $workerStore;
    }

    public function isStoreMappingExists($storeCode){
        $store =$this->workerStore->isValidStore($storeCode);
        $storeBaseUrl =false;
        if($store) {
            $baseUrl = $this->scopeConfig->getValue("web/secure/base_url");
            $storeBaseUrl = $baseUrl . $storeCode;
        }
        return $storeBaseUrl;
    }
}
