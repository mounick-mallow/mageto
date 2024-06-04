<?php

namespace Smartwave\Porto\Model\Config\Settings\General;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Notice option service
 */
class Notice implements OptionSourceInterface
{
    /**
     * To Option Array
     *
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '', 'label' => __('No')],
            ['value' => '1', 'label' => __('Above of the Header')],
            ['value' => '2', 'label' => __('Below of the Header')]
        ];
    }

    /**
     * T array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            '' => __('No'),
            '1' => __('Above of the Header'),
            '2' => __('Below of the Header')
        ];
    }
}
