<?php

namespace LuxuryUnlimited\GoogleAnalytics\Observer;

use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use Magento\Customer\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class ProductRemoveCartAfter implements ObserverInterface
{
    /**
     * @var Data
     */
    protected $analyticsData;

    /**
     * @var Session
     */
    protected $customerSession;

    /**
     * Constructor
     *
     * @param Data $analyticsData
     * @param Session $customerSession
     */
    public function __construct(
        Data $analyticsData,
        Session $customerSession
    ) {
        $this->analyticsData = $analyticsData;
        $this->customerSession = $customerSession;
    }

    /**
     * Execute
     *
     * @param Observer $observer
     * @return $this|void
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $data = [];
        $this->customerSession->unsGaRemovedProductData();
        $item = $observer->getData('quote_item');
        $data['product_id'] = $item->getProductId();
        $data['qty'] = $item->getQty();
        $this->customerSession->setGaRemovedProductData($data);

        return $this;
    }
}
