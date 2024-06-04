<?php

namespace WeltPixel\ProductPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class TabsVersion implements ArrayInterface
{

    /**
     * Return list of Accordion Version
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => '0',
                'label' => __('Version 1 - aligned left with border'),
            ],
            [
                'value' => '1',
                'label' => __('Version 2 - centered without border'),
            ]
        ];
    }
}
