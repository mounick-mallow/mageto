<?php

namespace LuxuryUnlimited\StoreSwitcher\Controller\GetBaseUrl;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\StoreRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use LuxuryUnlimited\WorkerStores\Helper\Data as workerHelper;
/**
 * @SuppressWarnings("PMD.AllPurposeAction")
 * @SuppressWarnings("PMD.ExcessiveMethodLength")
 */
class Index extends \Magento\Framework\App\Action\Action
{
    public const COOKIE_NAME = 'base_url';

    public const COOKIE_DURATION = '86400';

    /**
     * @var StoreManagerInterface $storeManager
     */
    protected $storemanager;

    /**
     * @var StoreRepositoryInterface
     */
    protected $storeRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     */
    protected $_cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     */
    protected $_cookieMetadataFactory;

    protected $workerHelper;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param StoreManagerInterface $storemanager
     * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     * @param \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory
     * @param StoreRepositoryInterface $storeRepository
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        StoreManagerInterface $storemanager,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadataFactory,
        StoreRepositoryInterface $storeRepository,
        workerHelper $workerHelper
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->storemanager = $storemanager;
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->storeRepository = $storeRepository;
        $this->workerHelper = $workerHelper;
        parent::__construct($context);
    }

    /**
     * Execute
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $storeCode = $data['storeCode'];
        $baseUrl = '';
        $storeBaseUrl = $this->workerHelper->isStoreMappingExists($storeCode);
        if($storeBaseUrl) {
            $baseUrl = $storeBaseUrl;
            $metadata = $this->_cookieMetadataFactory
                ->createPublicCookieMetadata()
                ->setDuration(self::COOKIE_DURATION)->setPath('/');
            $this->_cookieManager->setPublicCookie(self::COOKIE_NAME, $baseUrl, $metadata);
        }
        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData(["base_url" => $baseUrl]);
    }

    /**
     * @param $storeCode
     * @return null
     * @throws LocalizedException
     */
    public function getStoreByCode($storeCode)
    {
        $store = null;
        try {
            $store = $this->storeRepository->get($storeCode);
        } catch (\Exception $exception) {
            throw new LocalizedException(__($exception->getMessage(). $storeCode));
        }
        return $store;
    }
}
