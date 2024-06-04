<?php

namespace Smartwave\Porto\Model\Config\Settings\Footer\Middle;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Block option service
 */
class Block implements OptionSourceInterface
{
    /**
     * To option array
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '', 'label' => __('Do not show')],
            ['value' => 'custom', 'label' => __('Custom Block')],
            ['value' => 'newsletter', 'label' => __('Newsletter Subscribe')]
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
            '' => __('Do not show'),
            'custom' => __('Custom Block'),
            'newsletter' => __('Newsletter Subscribe')
        ];
    }
}
