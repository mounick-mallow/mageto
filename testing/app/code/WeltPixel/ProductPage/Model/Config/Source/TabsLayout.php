<?php

namespace WeltPixel\ProductPage\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class TabsLayout implements ArrayInterface
{
    public const TAB_TAB = 'tab';
    public const TAB_ACCORDION = 'accordion';
    public const TAB_LIST = 'list';

    /**
     * Return list of TabLayout Options
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::TAB_TAB,
                'label' => __('Tab')
            ],
            [
                'value' => self::TAB_ACCORDION,
                'label' => __('Accordion')
            ],
            [
                'value' => self::TAB_LIST,
                'label' => __('List')
            ]
        ];
    }
}
