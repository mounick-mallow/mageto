<?php

namespace LuxuryUnlimited\GoogleAnalytics\Block\Product;

use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use LuxuryUnlimited\GoogleAnalytics\Block\GoogleAnalyticsInterface;
use Magento\Catalog\Block\Product\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;

class AddToCart extends Template implements GoogleAnalyticsInterface
{
    private const EVENT_NAME = 'add_to_cart';

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
        $data = $this->customerSession->getGaAddedProductData();
        $this->customerSession->unsGaAddedProductData();
        return $this->analyticsData->getGoogleAnalyticsProductData($data, self::EVENT_NAME);
    }
}
