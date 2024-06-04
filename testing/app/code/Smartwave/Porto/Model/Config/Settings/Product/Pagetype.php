<?php

namespace Smartwave\Porto\Model\Config\Settings\Product;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Pagetype option service
 */
class Pagetype implements OptionSourceInterface
{
    /**
     * To option array
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '', 'label' => __('Custom')],
            ['value' => 'default', 'label' => __('Default')],
            ['value' => 'carousel', 'label' => __('Extended')],
            ['value' => 'fullwidth', 'label' => __('Full Width')],
            ['value' => 'grid', 'label' => __('Grid Images')],
            ['value' => 'sticky_right', 'label' => __('Sticky Right Info')],
            ['value' => 'wide_grid', 'label' => __('Vertical Tumbnail')]
        ];
    }

    /**
     * To array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            '' => __('Custom'),
            'defulat' => __('Default'),
            'carousel' => __('Extended'),
            'fullwidth' => __('Full Width'),
            'grid' => __('Grid Images'),
            'sticky_right' => __('Sticky Right Info'),
            'wide_grid' => __('Vertical Tumbnail')
        ];
    }
}
