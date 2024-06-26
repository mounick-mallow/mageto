<?php

namespace LuxuryUnlimited\GoogleAnalytics\Observer;

use LuxuryUnlimited\GoogleAnalytics\Model\Data;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * @SuppressWarnings(PHPMD.CookieAndSessionMisuse)
 */
class UpdateQty implements ObserverInterface
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
     * @var RequestInterface
     */
    protected $request;

    /**
     * Constructor
     *
     * @param Data $analyticsData
     * @param RequestInterface $request
     * @param Session $customerSession
     */
    public function __construct(
        Data $analyticsData,
        RequestInterface $request,
        Session $customerSession
    ) {
        $this->request = $request;
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
        $this->customerSession->setIsUpdateQty(1);
        $cart = $observer->getData('cart');
        if ($cart->getItems()) {
            $this->customerSession->setIsUpdateQty(1);
        }
        return $this;
    }
}
