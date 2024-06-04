<?php
namespace LiveChat\LiveChat\Controller\Adminhtml\SetProps;

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
     * Constructor
     *
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
     * ClearCache
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

        $this->configWriter->save('lc_block_config/custom_params/cart_products', $post['cart_products']);
        $this->configWriter->save('lc_block_config/custom_params/total_cart_value', $post['total_cart_value']);
        $this->configWriter->save('lc_block_config/custom_params/total_orders_count', $post['total_orders_count']);
        $this->configWriter->save('lc_block_config/custom_params/last_order_details', $post['last_order_details']);

        $result = $this->resultJsonFactory->create();

        $this->clearCache();
        return $result->setData(['success' => 'custom_params saved']);
    }
}
