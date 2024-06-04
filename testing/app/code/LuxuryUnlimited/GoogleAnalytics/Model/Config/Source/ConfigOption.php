<?php
/**
 * @category    LuxuryUnlimited
 * @package     LuxuryUnlimited_GoogleAnalytics
 * @copyright   2023 © Luxury Unlimited
 */
namespace LuxuryUnlimited\GoogleAnalytics\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ConfigOption implements OptionSourceInterface
{
    /**
     * Return event options on the configuration
     *
     * @return mixed
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'page_view', 'label' => __('Page View')],
        ];
    }
}