<?php

namespace LuxuryUnlimited\BrandList\Block;

use Magento\Framework\View\Element\Template\Context;
use LuxuryUnlimited\BrandList\Helper\Data;

class SectionTwo extends \Magento\Framework\View\Element\Template
{

    /**
     * @var Data $helper
     */
    private $_helper;

    /**
     * *
     * @param Context $context
     * @param Data $_helper
     */
    public function __construct(
        Context $context,
        Data $_helper
    ) {
        parent::__construct($context);
        $this->_helper = $_helper;
    }

    /**
     * *
     *
     * @return string
     */
    public function isEnabledSectionTwo()
    {
        return $this->_helper->isEnabledSectionTwo();
    }

    /**
     * *
     *
     * @return string
     */
    public function getSectionTwoProductCount()
    {
        return $this->_helper->getSectionTwoBrandCount();
    }

    /**
     * *
     *
     * @return array
     */
    public function getSectionTwoBrands()
    {
        return $this->_helper->getBrandListTwo();
    }

    /**
     * *
     *
     * @return string
     */
    public function getBrandMediaUrl()
    {
        return $this->_helper->getBrandMediaUrl();
    }

    /**
     * *
     *
     * @param string $urlKey
     *
     * @return string
     */
    public function getBrandUrl($urlKey)
    {
        return $this->_helper->getBaseUrl().'brand/'.$urlKey.'.html';
    }

    /**
     * Check Logo Exist
     *
     * @param string $path
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function checkLogoExist($path)
    {
        return $this->_helper->checkImageExist($path);
    }
}
