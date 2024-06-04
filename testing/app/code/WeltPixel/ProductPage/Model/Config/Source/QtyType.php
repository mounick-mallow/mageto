<?php

namespace WeltPixel\ProductPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class QtyType implements ArrayInterface
{
    public const QTY_DEFAULT = 0;
    public const QTY_SELECT = 'select';

    /**
     * Return list of QtyType Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::QTY_DEFAULT,
                'label' => __('Default Input')
            ],
            [
                'value' => self::QTY_SELECT,
                'label' => __('Dropdown')
            ]
        ];
    }
}
