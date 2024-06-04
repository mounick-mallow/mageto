<?php
namespace WeltPixel\ProductPage\ViewModel;

class Magnifier implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \WeltPixel\ProductPage\Helper\Data
     */
    protected $helper;

    /**
     * @param \WeltPixel\ProductPage\Helper\Data $helper
     */
    public function __construct(
        \WeltPixel\ProductPage\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * Is magnifier enabled
     *
     * @return string
     */
    public function isMagnifierEnabled()
    {
        return $this->helper->getMagnifierEnabled();
    }

    /**
     * Get magnifier event type
     *
     * @return string
     */
    public function getMagnifierEventType()
    {
        return $this->helper->getMagnifierEventType();
    }
}
