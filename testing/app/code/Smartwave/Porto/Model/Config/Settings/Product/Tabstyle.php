<?php

namespace Smartwave\Porto\Model\Config\Settings\Product;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Typestyle option service
 */
class Tabstyle implements OptionSourceInterface
{
    /**
     * To option array
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '', 'label' => __('Horizontal')],
            ['value' => 'vertical', 'label' => __('Vertical')],
            ['value' => 'accordion', 'label' => __('Accordion')],
            ['value' => 'sticky', 'label' => __('Sticky Tab')]
        ];
    }

    /**
     * To array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            '' => __('Horizontal'),
            'vertical' => __('Vertical'),
            'accordion' => __('Accordion'),
            'sticky' => __('Sticky Tab')
        ];
    }
}
