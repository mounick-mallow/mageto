<?php

namespace LuxuryUnlimited\GoogleAnalytics\Block\Product;

use LuxuryUnlimited\GoogleAnalytics\Block\GoogleAnalyticsInterface;
use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\Context;

class View extends AbstractProduct implements GoogleAnalyticsInterface
{
    private const EVENT_NAME = 'view_item';

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
        $data['product_id'] = $this->getProduct()->getId();
        return $this->analyticsData->getGoogleAnalyticsProductData($data, self::EVENT_NAME);
    }
}
