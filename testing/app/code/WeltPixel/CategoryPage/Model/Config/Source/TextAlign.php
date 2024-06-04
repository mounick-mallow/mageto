<?php

namespace WeltPixel\CategoryPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class TextAlign implements ArrayInterface
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
                'value' => 'left',
                'label' => 'Left',
            ],
            [
                'value' => 'center',
                'label' => 'Center',
            ]
        ];
    }
}
