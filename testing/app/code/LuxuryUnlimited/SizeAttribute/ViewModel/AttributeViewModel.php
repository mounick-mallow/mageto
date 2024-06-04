<?php

namespace LuxuryUnlimited\SizeAttribute\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Get Selected Attribute ID
 * Class AttributeViewModel
 */
class AttributeViewModel implements ArgumentInterface
{
    public const SIZE_ATTRIBUTE = 'pdp_page/size_attribute/attribute_option';
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface    $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get Store Configuration Value
     *
     * @return int
     */
    public function getSizeAttribute()
    {
        return $this->scopeConfig->getValue(self::SIZE_ATTRIBUTE);
    }
}
