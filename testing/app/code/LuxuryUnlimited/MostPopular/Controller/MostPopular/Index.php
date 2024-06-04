<?php
namespace LuxuryUnlimited\MostPopular\Controller\MostPopular;

use LuxuryUnlimited\MostPopular\Cron\MostPopularCron;

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
     * @var MostPopularCron
     */
    protected $mostPopular;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * *
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param MostPopularCron $mostPopular
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        MostPopularCron $mostPopular,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_pageFactory = $pageFactory;
        $this->mostPopular = $mostPopular;
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
            $this->mostPopular->execute();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
