<?php
namespace LuxuryUnlimited\Customer\Controller\Address;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Save Address
 *
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Index extends \Magento\Customer\Controller\Address\Index
{
    
    /**
     * Customer addresses list
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $customerId = $this->_getSession()->getCustomerId();
        $addresses = $this->customerRepository->getById($customerId)->getAddresses();

        if (count($addresses)) {
            return parent::execute(); // Proceed with the original method execution
        } else {
            $resultPage = $this->resultPageFactory->create();
            $block = $resultPage->getLayout()->getBlock('address_book');
            if ($block) {
                $block->setRefererUrl($this->_redirect->getRefererUrl());
            }
            return $resultPage;
        }
    }
}
