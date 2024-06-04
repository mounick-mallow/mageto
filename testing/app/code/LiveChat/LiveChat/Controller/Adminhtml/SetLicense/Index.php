<?php

namespace LiveChat\LiveChat\Controller\Adminhtml\SetLicense;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Cache\ManagerFactory;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class Index extends Action
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
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param WriterInterface $configWriter
     * @param ManagerFactory $cacheManagerFactory
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        WriterInterface $configWriter,
        ManagerFactory $cacheManagerFactory
    ) {
        parent::__construct($context);
        $this->resultJsonFactory = $resultJsonFactory;
        $this->configWriter = $configWriter;
        $this->cacheManagerFactory = $cacheManagerFactory;
    }

    /**
     * Clear Cache
     *
     * @return void
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
     * @return ResponseInterface|Json|ResultInterface
     */
    public function execute()
    {
        $post = $this->getRequest()->getPostValue();

        $this->configWriter->save('lc_block_config/account/license_email', $post['email']);
        $this->configWriter->save('lc_block_config/account/license_id', $post['license']);

        $this->configWriter->save('lc_block_config/custom_params/cart_products', '1');
        $this->configWriter->save('lc_block_config/custom_params/total_cart_value', '1');
        $this->configWriter->save('lc_block_config/custom_params/total_orders_count', '1');
        $this->configWriter->save('lc_block_config/custom_params/last_order_details', '1');

        $result = $this->resultJsonFactory->create();

        $this->clearCache();

        return $result->setData(['success' => 'license saved']);
    }
}
