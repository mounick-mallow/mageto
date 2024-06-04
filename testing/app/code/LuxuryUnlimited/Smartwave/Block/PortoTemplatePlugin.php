<?php

namespace LuxuryUnlimited\Smartwave\Block;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template as ViewTemplate;
use Magento\Store\Model\ScopeInterface;
use Smartwave\Porto\Model\Config\Backend\Image\Logo;
use Smartwave\Porto\Model\Config\Provider;
use Smartwave\Porto\Helper\Data as PortoHelper;
use Smartwave\Megamenu\Helper\Data as Helper;

/**
 * Class block Template
 */
class PortoTemplatePlugin extends \Smartwave\Porto\Block\Template
{
    protected $portoHelper;

    protected $helper;

    /**
     * Construct
     *
     * @param Provider $configProvider
     * @param Context $context
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Provider $configProvider,
        Context $context,
        Registry $coreRegistry,
        PortoHelper $portoHelper,
        Helper $helper,
        array $data = []
    ) {
        $this->portoHelper = $portoHelper;
        $this->helper = $helper;
        parent::__construct($configProvider, $context, $coreRegistry, $data);
    }

    public function getMenuHelper()
    {
        return $this->helper;
    }

    public function getPortoHelper()
    {
        return $this->portoHelper;
    }

}
