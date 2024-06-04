<?php
namespace LuxuryUnlimited\SaleProducts\Controller\SaleProducts;

use Exception;
use LuxuryUnlimited\SaleProducts\Cron\SaleProductsCron;

/**
 * @SuppressWarnings("PMD.AllPurposeAction")
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @var SaleProducts|SaleProductsCron
     */
    protected $saleProducts;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * *
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param SaleProductsCron $saleProducts
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        SaleProductsCron $saleProducts,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_pageFactory = $pageFactory;
        $this->saleProducts = $saleProducts;
        $this->logger = $logger;
        return parent::__construct($context); //@phpstan-ignore-line
    }

    /**
     * *
     *
     * @return void
     */
    public function execute()
    {
        try {
            $this->saleProducts->execute();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
