<?php

namespace LuxuryUnlimited\Checkout\Block\Onepage;

class Success extends \Magento\Checkout\Block\Onepage\Success
{
    /**
     * Get Order
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->_checkoutSession->getLastRealOrder();
    }
}
