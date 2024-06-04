<?php

namespace Magetop\GiftCard\Block\System\Config\Form\Field;

use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Backend system config datetime field renderer
 */
class Info extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     *
     * @phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Get Element Html
     *
     * @param AbstractElement $element
     * @return string
     * @codeCoverageIgnore
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = '<div style="background: #000080;color:#fff;padding: 10px;border-radius: 5px;text-align: center">
                  Gift Card Module
                </div>';

        return $html;
    }
}
