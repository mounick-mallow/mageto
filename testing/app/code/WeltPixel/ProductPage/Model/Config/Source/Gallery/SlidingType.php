<?php

namespace WeltPixel\ProductPage\Model\Config\Source\Gallery;

use Magento\Framework\Option\ArrayInterface;

class SlidingType implements ArrayInterface
{

    /**
     * Return list of SlidingType Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'slides',
                'label' => __('Slides')
            ],
            [
                'value' => 'thumbs',
                'label' => __('Thumbs')
            ]
        ];
    }
}
