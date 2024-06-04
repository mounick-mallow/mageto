<?php

namespace Belvg\DonationUpdate\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const XML_DEFAULT_PRODUCT_PRICE = 'firas_donation_product/general/default_product_price';

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function getDefaultDonationPrice()
    {
        return $this->scopeConfig->getValue(self::XML_DEFAULT_PRODUCT_PRICE, ScopeInterface::SCOPE_STORE);
    }
}
