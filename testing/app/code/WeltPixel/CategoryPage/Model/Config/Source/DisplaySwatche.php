<?php

namespace WeltPixel\CategoryPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class DisplaySwatche implements ArrayInterface
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
                'value' => '0',
                'label' => 'No',
            ],
            [
                'value' => '1',
                'label' => 'Yes',
            ],
            [
                'value' => '2',
                'label' => 'On Hover',
            ]
        ];
    }
}
