<?php

namespace Belvg\LoggedCustomer\ViewModel;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Customer implements ArgumentInterface
{
    public CustomerSession $customerSession;

    public function __construct(CustomerSession $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    public function isCustomerLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn();
    }

    public function getCustomerData(): \Magento\Customer\Model\Customer
    {
        return $this->customerSession->getCustomer();
    }
}
