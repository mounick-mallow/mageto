<?php

namespace Smartwave\Porto\Model\Config\Settings\General;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Layout option array
 */
class Layout implements OptionSourceInterface
{
    /**
     * To option array
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            ['value' => '1140', 'label' => __('1140px (Default)')],
            ['value' => '1280', 'label' => __('1280px')],
            ['value' => 'full_width', 'label' => __('Full Width')]
        ];
    }

    /**
     * To array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            '1140' => __('1140px (Default)'),
            '1280' => __('1280px'),
            'full_width' => __('Full Width')
        ];
    }
}
