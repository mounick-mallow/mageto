<?php

namespace WeltPixel\ProductPage\Model\Config\Source\Gallery;

use Magento\Framework\Option\ArrayInterface;

class NavType implements ArrayInterface
{

    /**
     * Return list of NavType Options
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
                'value' => 'slides',
                'label' => __('Slides')
            ]
        ];
    }
}
