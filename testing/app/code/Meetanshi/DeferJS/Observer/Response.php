<?php
/**
 * Meetanshi_DeferJS
 *
 * @copyright   Copyright (c) 2023 IdeaInYou
 * @author      RuslanP <ruslan.p@ideainyou.com>
 */
namespace Meetanshi\DeferJS\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Meetanshi\DeferJS\Model\Config\Provider;
use Meetanshi\DeferJS\Model\DeferJsService;

/**
 * Class Observer
 */
class Response implements ObserverInterface
{
    /**
     * @var DeferJsService
     */
    private DeferJsService $deferJsService;

    /**
     * @var Provider
     */
    private Provider $configDataProvider;

    /**
     * Observer constructor.
     * @param Provider $configDataProvider
     * @param DeferJsService $deferJsService
     */
    public function __construct(
        Provider $configDataProvider,
        DeferJsService $deferJsService
    ) {
        $this->deferJsService = $deferJsService;
        $this->configDataProvider = $configDataProvider;
    }

    /**
     * Function execute
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        if (!$this->configDataProvider->isEnabled()) {
            return;
        }
        $response = $observer->getEvent()->getData('response');
        if (!$response) {
            return;
        }
        $this->deferJsService->modifyResponseBody($response);
    }
}
