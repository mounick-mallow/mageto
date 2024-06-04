<?php

namespace Belvg\LoggedCustomer\Controller\Index;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\Account\Redirect as AccountRedirect;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;

class Post extends Action
{
    public Session $session;

    public JsonFactory $jsonFactory;

    public AccountManagementInterface $accountManagement;
    public AccountRedirect $accountRedirect;

    public Validator $validator;

    /**
     *
     * @param Context $context
     * @param Session $customerSession
     * @param JsonFactory $jsonFactory
     * @param AccountManagementInterface $accountManagement
     * @param AccountRedirect $accountRedirect
     * @param Validator $validator
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        JsonFactory $jsonFactory,
        AccountManagementInterface $accountManagement,
        AccountRedirect $accountRedirect,
        Validator $validator
    ) {
        $this->session = $customerSession;
        $this->jsonFactory = $jsonFactory;
        $this->accountManagement = $accountManagement;
        $this->accountRedirect = $accountRedirect;
        $this->validator = $validator;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();

        if (!$this->validator->validate($this->getRequest())) {
            return $resultJson->setData(['success' => false, 'error' => __('Incorrect form key')]);
        }

        $email = $this->getRequest()->getParam('email');
        $password = $this->getRequest()->getParam('password');

        try {
            $customer = $this->accountManagement->authenticate($email, $password);
            $this->session->setCustomerDataAsLoggedIn($customer);
            $this->session->regenerateId();

            return $resultJson->setData(['success' => true]);
        } catch (\Exception $e) {
            return $resultJson->setData(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
