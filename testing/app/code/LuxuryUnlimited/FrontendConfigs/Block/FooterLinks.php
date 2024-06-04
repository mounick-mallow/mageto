<?php

namespace LuxuryUnlimited\FrontendConfigs\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use LuxuryUnlimited\FrontendConfigs\Helper\Data;
use Magento\Framework\UrlInterface;

class FooterLinks extends Template
{
    protected const PATH_FOOTER_LINK = 'frontendconfigs/footer/';

    /**
     * @var \LuxuryUnlimited\FrontendConfigs\Helper\Data
     */
    protected $_helperData;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    
    protected $_urlInterface;

    /**
     * FooterLinks constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \LuxuryUnlimited\FrontendConfigs\Helper\Data $helperData
     * @param \Magento\Framework\UrlInterface $urlInterface
     */
    public function __construct(
        Context $context,
        Data $helperData,
        UrlInterface $urlInterface
    ) {
        $this->_helperData = $helperData;
        $this->_urlInterface = $urlInterface;
        parent::__construct($context);
    }

    /**
     * Get footer link
     *
     * @param string $path
     *
     * @return string
     */
    public function getFooterLink($path)
    {
        $link = $this->_helperData->getConfig(self::PATH_FOOTER_LINK.$path);
        if ($link != '') {
            return $this->_urlInterface->getBaseUrl().$link;
        }

        return '';
    }

    /**
     * Get Social link
     *
     * @param string $path
     *
     * @return string
     */
    public function getSocialLink($path)
    {
        $link = $this->_helperData->getConfig(self::PATH_FOOTER_LINK.$path);
        if ($link != '') {
            return $link;
        }

        return '';
    }
}
