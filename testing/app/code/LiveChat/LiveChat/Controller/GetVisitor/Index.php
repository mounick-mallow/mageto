<?php

namespace LiveChat\LiveChat\Controller\GetVisitor;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * @SuppressWarnings(PHPMD.AllPurposeAction)
 */
class Index extends Action
{
    /**
     * @var Session
     */
    protected $_customerSession;

    /**
     * @var Http
     */
    private $response;

    /**
     * @var Json
     */
    protected $_json;

    /**
     * Construct
     *
     * @param Context $context
     * @param Session $customerSession
     * @param Json $json
     * @param Http $response
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        Json $json,
        Http $response
    ) {
        $this->_customerSession = $customerSession;
        $this->response = $response;
        $this->_json = $json;
        parent::__construct($context);
    }

    /**
     * Execute
     *
     * @return Http
     */
    public function execute()
    {
        $visitor_data = 'var livechat_visitor_data = ' . $this->_json->serialize($this->getCustomerDetails());
        $this->response->setHeader('Content-type', 'application/javascript', true);
        $this->response->setBody($visitor_data);
        return $this->response;
    }

    /**
     * Returns last order details.
     *
     * @return string
     */
    public function getCustomerDetails()
    {
        $result = [];

        if (null !== ($email = $this->getCustomerEmail())) {
            $result['email'] =  $email;
        }

        if (null !== ($name = trim($this->getCustomerName())) && '' !== $name) {
            $result['name'] = $name;
        }
        return $result;
    }

    /**
     * Returns customers email.
     *
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->_customerSession->getCustomer()->getEmail();
    }

    /**
     * Returns customers name.
     *
     * @return string
     */
    public function getCustomerName()
    {
        return $this->_customerSession->getCustomer()->getName();
    }
}
