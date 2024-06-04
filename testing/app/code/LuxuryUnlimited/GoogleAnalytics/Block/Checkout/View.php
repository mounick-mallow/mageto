<?php

namespace LuxuryUnlimited\GoogleAnalytics\Block\Checkout;

use LuxuryUnlimited\GoogleAnalytics\Block\GoogleAnalyticsInterface;
use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

class View extends Template implements GoogleAnalyticsInterface
{
    private const EVENT_NAME = 'begin_checkout';

    /**
     * @var Data
     */
    protected $analyticsData;

    /**
     * Constructor
     *
     * @param Data $analyticsData
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Data $analyticsData,
        Context $context,
        array $data = []
    ) {
        $this->analyticsData = $analyticsData;

        parent::__construct($context, $data);
    }

    /**
     * Get GA script data
     *
     * @return array
     */
    public function getGoogleAnalyticsScriptData(): array
    {
        return $this->analyticsData->getGoogleAnalyticsCartData(self::EVENT_NAME);
    }
}
