<?php

namespace WeltPixel\ProductPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class PositionProductInfo implements ArrayInterface
{

    /**
     * Return list of PositionProductInfo Version
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '0',
                'label' => 'Fixed',
            ],
            [
                'value' => '1',
                'label' => 'Vertical Sliding with Scroll',
            ]
        ];
    }
}
