<?php
namespace LuxuryUnlimited\Customer\ViewModel;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\View\Element\Block\ArgumentInterface;

/**
 * Get Customer Address Count
 * Class CustomerAddressCount
 */
class CustomerAddressCount implements ArgumentInterface
{
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * CustomerAddressCount constructor.
     *
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerSession $customerSession
     */
    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        CustomerSession $customerSession
    ) {
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * Get Address Count
     *
     * @return int
     */
    public function getAddressCount()
    {
        $customerId = $this->customerSession->getCustomerId();
        
        if ($customerId) {
            $customer = $this->customerRepository->getById($customerId);
            return count($customer->getAddresses());
        }
        
        return 0; // Default to 0 if customer ID is not available
    }
}
