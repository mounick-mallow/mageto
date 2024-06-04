<?php

namespace Smartwave\Porto\Model\Config\Settings\Category;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Description option service
 */
class Description implements OptionSourceInterface
{
    /**
     * To option array
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '', 'label' => __('Default')],
            [
                'value' => 'full_width',
                'label' => __('As Full Width below the Header')
            ],
            [
                'value' => 'main_column',
                'label' => __('Main Column above the Toolbar')
                ]
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
            '' => __('Default'),
            'full_width' => __('As Full Width below the Header'),
            'main_column' => __('Main Column above the Toolbar')
        ];
    }
}
