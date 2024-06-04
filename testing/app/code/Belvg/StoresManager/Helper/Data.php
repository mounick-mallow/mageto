<?php

namespace Belvg\StoresManager\Helper;


use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;


class Data extends AbstractHelper
{
    const XML_STORE_NAME_PATH = 'general/store_information/name';

    const XML_STORE_SUPPORT_EMAIL = 'trans_email/ident_support/email';

    /**
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function getStoreName()
    {
        return $this->scopeConfig->getValue(self::XML_STORE_NAME_PATH, ScopeInterface::SCOPE_STORE);
    }

    public function getSupportEmail()
    {
        return $this->scopeConfig->getValue(self::XML_STORE_SUPPORT_EMAIL, ScopeInterface::SCOPE_STORE);
    }
}
