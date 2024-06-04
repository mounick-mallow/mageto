<?php

namespace WeltPixel\ProductPage\Model\Config\Source\Gallery;

use Magento\Framework\Option\ArrayInterface;

class NavStyle implements ArrayInterface
{

    /**
     * Return list of NavStyle Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'thumbs',
                'label' => __('Thumbs')
            ],
            [
                'value' => 'dots',
                'label' => __('Dots')
            ],
            [
                'value' => 'false',
                'label' => __('None')
            ]
        ];
    }
}
