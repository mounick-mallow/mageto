<?php

namespace LuxuryUnlimited\GoogleAnalytics\Block\Checkout;

use LuxuryUnlimited\GoogleAnalytics\Block\GoogleAnalyticsInterface;
use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class PlaceOrderBefore extends Template implements GoogleAnalyticsInterface
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
        $quoteId = $this->customerSession->getGaBeforePlaceQuoteId();
        $this->customerSession->unsGaBeforePlaceQuoteId();
        return $quoteId ? $this->analyticsData->getGoogleAnalyticsQuoteData($quoteId) : [];
    }
}
