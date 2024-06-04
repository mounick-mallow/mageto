<?php

namespace Smartwave\Porto\Block\System\Config\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Registry;

/**
 * Class change field color
 */
class Color extends Field
{
    /**
     * @var Registry
     */
    protected Registry $coreRegistry;

    /**
     * Construct
     *
     * @param Context $context
     * @param Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Overwritten parent _getElementHtml()
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $html = $element->getElementHtml();
        if (!$this->coreRegistry->registry('colorpicker_loaded')) {
            $this->coreRegistry->registry('colorpicker_loaded');
        }
        $html .= '<script type="text/javascript">
                let el = document.getElementById("'. $element->getHtmlId() .'");
                el.className = el.className + " jscolor {refine:false}";
            </script>';

        return $html;
    }
}
