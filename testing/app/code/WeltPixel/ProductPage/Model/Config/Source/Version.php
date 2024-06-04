<?php

namespace WeltPixel\ProductPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Version implements ArrayInterface
{

    /**
     * Return list of QtyType Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 1,
                'label' => __('Version 1')
            ],
            [
                'value' => 2,
                'label' => __('Version 2')
            ],
            [
                'value' => 3,
                'label' => __('Version 3')
            ],
            [
                'value' => 4,
                'label' => __('Version 4')
            ]
        ];
    }
}
