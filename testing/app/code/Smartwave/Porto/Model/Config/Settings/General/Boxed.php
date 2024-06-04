<?php

namespace Smartwave\Porto\Model\Config\Settings\General;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Boxed option service
 */
class Boxed implements OptionSourceInterface
{
    /**
     * To option array
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'wide', 'label' => __('Wide (Default)')],
            ['value' => 'boxed', 'label' => __('Boxed')]
        ];
    }

    /**
     * To array
     *
     * @return array
     */
    public function toArray(): array
    {
        return ['wide' => __('Wide (Default)'), 'boxed' => __('Boxed')];
    }
}
