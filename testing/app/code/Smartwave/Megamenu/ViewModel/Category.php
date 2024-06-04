<?php

namespace Smartwave\Megamenu\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Smartwave\Megamenu\Helper\Data as MegamenuHelper;
use Smartwave\Porto\Helper\Data as PortoHelper;

/**
 * class view model mega menu
 */
class Category implements ArgumentInterface
{
    /**
     * @var MegamenuHelper
     */
    protected $megamenuHelper;

    /**
     * @var PortoHelper
     */
    protected $portoHelper;

    /**
     * Constructor
     *
     * @param MegamenuHelper $megamenuHelper
     * @param PortoHelper $portoHelper
     */
    public function __construct(
        MegamenuHelper $megamenuHelper,
        PortoHelper $portoHelper
    ) {
        $this->megamenuHelper = $megamenuHelper;
        $this->portoHelper = $portoHelper;
    }

    /**
     * Return megamenu helper
     *
     * @return mixed
     */
    public function getMegamenuHelper()
    {
        return $this->megamenuHelper;
    }

    /**
     * Return porto helper
     *
     * @return mixed
     */
    public function getPortoHelper()
    {
        return $this->portoHelper;
    }
}
