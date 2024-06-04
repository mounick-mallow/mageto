<?php

namespace LuxuryUnlimited\GoogleAnalytics\Block\Cart;

use LuxuryUnlimited\GoogleAnalytics\Block\GoogleAnalyticsInterface;
use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class UpdateQty extends Template implements GoogleAnalyticsInterface
{
    private const EVENT_NAME = 'add_to_cart';

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
        $isUpdateQty = $this->customerSession->getIsUpdateQty();
        $this->customerSession->unsIsUpdateQty();
        return $isUpdateQty ? $this->analyticsData->getGoogleAnalyticsCartData(self::EVENT_NAME) : [];
    }
}
