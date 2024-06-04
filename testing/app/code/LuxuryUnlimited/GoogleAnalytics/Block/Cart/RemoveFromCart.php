<?php

namespace LuxuryUnlimited\GoogleAnalytics\Block\Cart;

use LuxuryUnlimited\GoogleAnalytics\Block\GoogleAnalyticsInterface;
use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use Magento\Catalog\Block\Product\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;

class RemoveFromCart extends Template implements GoogleAnalyticsInterface
{
    private const EVENT_NAME = 'remove_from_cart';

    /**
     * @var Data
     */
    protected Data $analyticsData;

    /**
     * @var Session
     */
    private Session $customerSession;

    /**
     * Constructor
     *
     * @param Data $analyticsData
     * @param Session $customerSession
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Data $analyticsData,
        Session $customerSession,
        Context $context,
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
        $data = $this->customerSession->getGaRemovedProductData();
        $this->customerSession->unsGaRemovedProductData();
        return $this->analyticsData->getGoogleAnalyticsProductData($data, self::EVENT_NAME);
    }
}
