<?php

namespace Sololuxury\BuyNow\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Provider
{
    /**
     * Buynow button title path
     */
    private const BUYNOW_BUTTON_TITLE_PATH = 'buynow/general/button_title';

    /**
     * Buynow button title
     */
    private const BUYNOW_BUTTON_TITLE = 'Buy Now';

    /**
     * Addtocart button form id path
     */
    private const ADDTOCART_FORM_ID_PATH = 'buynow/general/addtocart_id';

    /**
     * Addtocart button form id
     */
    private const ADDTOCART_FORM_ID = 'product_addtocart_form';

    /**
     * Keep cart products path
     */
    private const KEEP_CART_PRODUCTS_PATH = 'buynow/general/keep_cart_products';

    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * Construct
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve config value
     * // phpcs:ignore "Magento2.Annotation.MethodArguments.NoTypeSpecified: Type is not specified"
     *
     * @param mixed $config
     * @return string
     */
    public function getConfig(mixed $config): string
    {
        return $this->scopeConfig->getValue(
            $config,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get button title
     *
     * @return string
     */
    public function getButtonTitle(): string
    {
        $btnTitle = $this->getConfig(self::BUYNOW_BUTTON_TITLE_PATH);
        return $btnTitle ?: self::BUYNOW_BUTTON_TITLE;
    }

    /**
     * Get addtocart form id
     *
     * @return string
     */
    public function getAddToCartFormId(): string
    {
        $addToCartFormId = $this->getConfig(self::ADDTOCART_FORM_ID_PATH);
        return $addToCartFormId ?: self::ADDTOCART_FORM_ID;
    }

    /**
     * Check if keep cart products
     *
     * @return string
     */
    public function keepCartProducts(): string
    {
        return $this->getConfig(self::KEEP_CART_PRODUCTS_PATH);
    }
}
