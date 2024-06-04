<?php

namespace WeltPixel\ProductPage\Model\Config\Source\Gallery;

use Magento\Framework\Option\ArrayInterface;

class EventType implements ArrayInterface
{

    /**
     * Return list of EventType Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'hover',
                'label' => __('Hover')
            ],
            [
                'value' => 'click',
                'label' => __('Click')
            ]
        ];
    }
}
