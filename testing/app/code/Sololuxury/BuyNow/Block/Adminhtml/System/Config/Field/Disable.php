<?php

namespace Sololuxury\BuyNow\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Class Block Disable
 */
class Disable extends Field
{
    /**
     * Get Element Html
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $element->setData('disabled', 'disabled');
        return $element->getElementHtml();
    }
}
