<?php

namespace LuxuryUnlimited\FrontendConfigs\Block\Account;

class Navigation extends \Magento\Customer\Block\Account\Navigation
{
    /**
     * Render Block
     *
     * @param \Magento\Framework\View\Element\AbstractBlock $link
     * @return string
     */
    public function renderLink(\Magento\Framework\View\Element\AbstractBlock $link)
    {
        if ($link->getLabel() != '') {
            $label = strtolower(str_replace(' ', '', $link->getLabel()));
            return str_replace("nav", $label." nav", $this->_layout->renderElement($link->getNameInLayout()));
        }
    }
}
