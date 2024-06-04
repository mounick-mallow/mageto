<?php

namespace Belvg\CountryCodes\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Belvg\CountryCodes\Helper\Data;


class Index extends  Action implements HttpGetActionInterface
{
    public JsonFactory $jsonFactory;

    public Data $helper;

    /**
     *
     * @param Context $context
     * @param JsonFactory $jsonFactory
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        Data $helper
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->helper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        $countryId = $this->getRequest()->getParam('country_id');
        $resultJson = $this->jsonFactory->create();
        if (empty($countryId)) {
            $result = ['error' => __('Country Id is required.')];
            return $resultJson->setData($result);
        }

        $phoneCode = $this->helper->getPhoneCode($countryId);
        $result = !empty($phoneCode) ?
            ['success' => true, 'phone_code' => $phoneCode] :
            ['success' => false, 'error' => __('Country phone code not found.')];

        return $resultJson->setData($result);
    }
}
