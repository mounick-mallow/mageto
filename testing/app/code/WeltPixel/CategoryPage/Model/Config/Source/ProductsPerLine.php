<?php

namespace WeltPixel\CategoryPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class ProductsPerLine implements ArrayInterface
{

    /**
     * To options to array
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '2',
                'label' => '2',
            ],
            [
                'value' => '3',
                'label' => '3',
            ],
            [
                'value' => '4',
                'label' => '4',
            ],
            [
                'value' => '5',
                'label' => '5',
            ]
        ];
    }
}
