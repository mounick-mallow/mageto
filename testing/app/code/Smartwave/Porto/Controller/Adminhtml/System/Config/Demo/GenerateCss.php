<?php
namespace Smartwave\Porto\Controller\Adminhtml\System\Config\Demo;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Smartwave\Porto\Model\Cssconfig\Generator;

/**
 * Class controller GenerateCss
 */
class GenerateCss extends Action
{
    /**
     * @var string[]
     */
    protected $_publicActions = ['generatecss'];

    /**
     * @var Generator
     */
    private Generator $generator;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Construct
     *
     * @param Context $context
     * @param Generator $generator
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        Generator $generator,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->generator = $generator;
        $this->logger = $logger;
    }

    /**
     * Function execute()
     *
     * @return Redirect|ResultInterface|(Redirect&ResultInterface)
     */
    public function execute()
    {
        try {
            $this->generator->generateCss('design', '', '');
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        try {
            $this->generator->generateCss('settings', '', '');
        } catch (NoSuchEntityException|LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($this->_redirect->getRefererUrl());

        return $resultRedirect;
    }
}
