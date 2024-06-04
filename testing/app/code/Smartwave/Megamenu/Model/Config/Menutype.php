<?php

namespace Smartwave\Megamenu\Model\Config;

class Menutype implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Return menu type option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'fullwidth', 'label' => __('Full Width')],
            ['value' => 'staticwidth', 'label' => __('Static Width')],
            ['value' => 'classic', 'label' => __('Classic')]
        ];
    }

    /**
     * Return menu type array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'fullwidth' => __('Full Width'),
            'staticwidth' => __('Static Width'),
            'classic' => __('Classic')
        ];
    }
}
