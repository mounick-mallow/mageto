<?php

declare(strict_types=1);

namespace Intobi\ERP\Model\PageBuilder;

use Exception;
use Throwable;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class PageSave implements ObserverInterface
{
    /**
     * @var Api
     */
    private $api;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param Api $api
     * @param LoggerInterface $logger
     */
    public function __construct(
        Api $api,
        LoggerInterface $logger
    ) {
        $this->api = $api;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        try {
            if (!$this->api->isEnable() || !$this->api->hasSendRequest()) {
                return;
            }

            $page = $observer->getPage();

            if (!$page) {
                return;
            }

            $domain = $_SERVER['HTTP_HOST'] ?? '';

            $data = $page->getData();
            $data['magento_domain'] = $domain;

            $this->api->sendPageData($data);
        } catch (Exception|Throwable $e) {
            $this->logger->error('ObserverPageSave error: ' . $e->getMessage());
        }
    }
}
