<?php

namespace LuxuryUnlimited\GoogleAnalytics\Block\Checkout;

use LuxuryUnlimited\GoogleAnalytics\Block\GoogleAnalyticsInterface;
use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class PlaceOrderAfter extends Template implements GoogleAnalyticsInterface
{
    /**
     * @var Data
     */
    protected $analyticsData;

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * Constructor
     *
     * @param Data $analyticsData
     * @param Context $context
     * @param Session $customerSession
     * @param array $data
     */
    public function __construct(
        Data $analyticsData,
        Context $context,
        Session $customerSession,
        array $data = []
    ) {
        $this->analyticsData = $analyticsData;
        $this->customerSession = $customerSession;

        parent::__construct($context, $data);
    }

    /**
     * Get GA script data
     *
     * @return array
     */
    public function getGoogleAnalyticsScriptData(): array
    {
        $orderId = $this->customerSession->getGaPlacedOrderId();
        $this->customerSession->unsGaPlacedOrderId();
        return $orderId ? $this->analyticsData->getGoogleAnalyticsOrderData($orderId) : [];
    }
}
