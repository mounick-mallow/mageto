<?php

namespace Smartwave\Porto\Model\Config\Settings\Newsletter;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Enable option service
 */
class Enable implements OptionSourceInterface
{
    /**
     * To option array
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '0', 'label' => __('Disable')],
            ['value' => '1', 'label' => __('Enable on Only Homepage')],
            ['value' => '2', 'label' => __('Enable on All Pages')]
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
            '0' => __('Disable'),
            '1' => __('Enable on Only Homepage'),
            '2' => __('Enable on All Pages')
        ];
    }
}
