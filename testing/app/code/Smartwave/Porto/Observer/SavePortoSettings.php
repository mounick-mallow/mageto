<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Smartwave\Porto\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Psr\Log\LoggerInterface;
use Smartwave\Porto\Model\Cssconfig\Generator;

/**
 * Class observer SavePortoSettings
 */
class SavePortoSettings implements ObserverInterface
{
    /**
     * @var Generator
     */
    protected Generator $cssGenerator;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param Generator $cssGenerator
     * @param LoggerInterface $logger
     */
    public function __construct(
        Generator $cssGenerator,
        LoggerInterface $logger
    ) {
        $this->cssGenerator = $cssGenerator;
        $this->logger = $logger;
    }

    /**
     * Log out user and redirect to new admin custom url
     *
     * @param Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer): void
    {
        try {
            $this->cssGenerator->generateCss(
                'settings',
                $observer->getData("website"),
                $observer->getData("store")
            );
        } catch (NoSuchEntityException| LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
