<?php

namespace LuxuryUnlimited\StoreSwitcher\Controller\GetStores;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;
use Psr\Log\LoggerInterface;
use Exception;
use LuxuryUnlimited\StoreSwitcher\Model\WebsitesStoresRepository;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * @SuppressWarnings("PMD.AllPurposeAction")
 * @SuppressWarnings("PMD.ExcessiveMethodLength")
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var StoreManagerInterface $storemanager
     */
    protected $storemanager;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var WebsiteRepositoryInterface
     */
    public $websiteRepository;

    /**
     * @var LoggerInterface
     */
    public $logger;

    /**
     * @var WebsitesStoresRepository
     */
    public $websitesStoresRepository;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param StoreManagerInterface $storemanager
     * @param LoggerInterface $logger
     * @param WebsiteRepositoryInterface $websiteRepository
     * @param WebsitesStoresRepository $websitesStoresRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        StoreManagerInterface $storemanager,
        LoggerInterface $logger,
        WebsiteRepositoryInterface $websiteRepository,
        WebsitesStoresRepository $websitesStoresRepository
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->storemanager = $storemanager;
        $this->logger = $logger;
        $this->websiteRepository =  $websiteRepository;
        $this->websitesStoresRepository = $websitesStoresRepository;
        parent::__construct($context);
    }

    /**
     * Execute
     */
    public function execute()
    {
        $storeData = [];
        $data = $this->getRequest()->getParams();
        if(isset($data['websiteCode'])){
            $stores = $this->getStoreBySite($data['websiteCode']);
            foreach ($stores as $store) {
                $storeData[] = $store;
            }
        }

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($storeData);
    }

    /**
     * Get website id
     *
     * @param string $code
     * @return int|null
     */
    public function getWebsiteId($code)
    {
        $websiteId = null;
        try {
            $website = $this->websiteRepository->get($code);
            $websiteId = (int)$website->getId();
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
        }
        return $websiteId;
    }

    /**
     * @param $websiteCode
     * @return array
     */
    public function getStoreBySite($websiteCode)
    {
        $websites = $this->websitesStoresRepository->getWebsites();
        $stores = [];
        foreach ($websites as $website) {
            $code = $website['code'];
            $storeList   = $website['store_list'];
            $currency = $website['default_display_currency_code'];
            if (count($storeList) && $code == $websiteCode) {
                array_push($storeList,['currency'=>$website['default_display_currency_code']]);
                $stores  = $storeList;
            }
        }
        return $stores;
    }
}
