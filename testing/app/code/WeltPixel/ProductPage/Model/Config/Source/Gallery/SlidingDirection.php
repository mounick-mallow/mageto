<?php

namespace WeltPixel\ProductPage\Model\Config\Source\Gallery;

use Magento\Framework\Option\ArrayInterface;

class SlidingDirection implements ArrayInterface
{

    /**
     * Return list of SlidingDirection Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'horizontal',
                'label' => __('Horizontal')
            ],
            [
                'value' => 'vertical',
                'label' => __('Vertical')
            ]
        ];
    }
}
