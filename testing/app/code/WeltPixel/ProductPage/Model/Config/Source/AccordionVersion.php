<?php

namespace WeltPixel\ProductPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class AccordionVersion implements ArrayInterface
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
                'label' => 'Version 1',
            ],
            [
                'value' => '1',
                'label' => 'Version 2',
            ]
        ];
    }
}
