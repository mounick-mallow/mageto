<?php

namespace WeltPixel\ProductPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class TrueFalse implements ArrayInterface
{

    /**
     * Return list of TrueFalse Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'true',
                'label' => __('True')
            ],
            [
                'value' => 'false',
                'label' => __('False')
            ]
        ];
    }
}
