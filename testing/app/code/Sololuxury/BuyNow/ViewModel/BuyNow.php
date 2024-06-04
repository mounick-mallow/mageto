<?php

namespace Sololuxury\BuyNow\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Sololuxury\BuyNow\Model\Config\Provider;

/**
 * Class View Model BuyNow
 */
class BuyNow implements ArgumentInterface
{
    /**
     * @var Provider
     */
    private Provider $provider;

    /**
     * @param Provider $provider
     */
    public function __construct(
        Provider $provider
    ) {
        $this->provider = $provider;
    }

    /**
     * Get Button Title
     *
     * @return string
     */
    public function getButtonTitle(): string
    {
        return $this->provider->getButtonTitle();
    }

    /**
     * Get Add To Cart Form Id
     *
     * @return string
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function getAddToCartFormId(): string
    {
        return $this->provider->getAddToCartFormId();
    }
}
