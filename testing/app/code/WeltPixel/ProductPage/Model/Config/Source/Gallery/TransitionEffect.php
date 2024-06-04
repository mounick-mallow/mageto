<?php

namespace WeltPixel\ProductPage\Model\Config\Source\Gallery;

use Magento\Framework\Option\ArrayInterface;

class TransitionEffect implements ArrayInterface
{

    /**
     * Return list of TransitionEffect Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'slide',
                'label' => __('Slide')
            ],
            [
                'value' => 'crossfade',
                'label' => __('Crossfade')
            ],
            [
                'value' => 'dissolve',
                'label' => __('Dissolve')
            ]
        ];
    }
}
