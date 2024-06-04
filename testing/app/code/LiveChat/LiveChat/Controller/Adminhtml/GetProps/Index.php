<?php

namespace LiveChat\LiveChat\Controller\Adminhtml\GetProps;

use LiveChat\LiveChat\Helper\Data;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\ManagerFactory;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * @var ManagerFactory
     */
    private $cacheManagerFactory;

    /**
     * @var Data
     */
    private $dataHelper;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Data $dataHelper
     * @param JsonFactory $resultJsonFactory
     * @param WriterInterface $configWriter
     * @param ManagerFactory $cacheManagerFactory
     */
    public function __construct(
        Context $context,
        Data $dataHelper,
        JsonFactory $resultJsonFactory,
        WriterInterface $configWriter,
        ManagerFactory $cacheManagerFactory
    ) {
        parent::__construct($context);
        $this->dataHelper = $dataHelper;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->configWriter = $configWriter;
        $this->cacheManagerFactory = $cacheManagerFactory;
    }

    /**
     * ClearCache
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
     */
    private function clearCache()
    {
        $cacheManager = $this->cacheManagerFactory->create();
        $types = $cacheManager->getAvailableTypes();
        $cacheManager->clean($types);
    }

    /**
     * Execute
     *
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $license_settings = [];

        $license_settings['license_email'] = $this->dataHelper->getLicenseEmail();
        $license_settings['cart_products'] = $this->dataHelper->showCustomParam(Data::LC_CP_SHOW_CART_PRODUCTS);
        $license_settings['total_cart_value'] =
                $this->dataHelper->showCustomParam(Data::LC_CP_SHOW_TOTAL_CART_VALUE);
        $license_settings['total_orders_count'] =
                $this->dataHelper->showCustomParam(Data::LC_CP_SHOW_TOTAL_ORDERS_COUNT);
        $license_settings['last_order_details'] =
                $this->dataHelper->showCustomParam(Data::LC_CP_SHOW_LAST_ORDER_DETAILS);

        $result = $this->resultJsonFactory->create();

        return $result->setData(['license_settings' => json_encode($license_settings)]);
    }
}
