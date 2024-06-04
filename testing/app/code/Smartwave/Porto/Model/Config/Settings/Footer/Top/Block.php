<?php

namespace Smartwave\Porto\Model\Config\Settings\Footer\Top;

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
            ['value' => 'custom', 'label' => __('Custom Block')]
        ];
    }

    /**
     * To array
     *
     * @return array
     */
    public function toArray(): array
    {
        return ['' => __('Do not show'), 'custom' => __('Custom Block')];
    }
}
