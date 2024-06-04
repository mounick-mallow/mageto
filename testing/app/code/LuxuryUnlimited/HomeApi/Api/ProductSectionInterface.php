<?php
/**
 * LuxuryUnlimited_HomeApi
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\HomeApi\Api;

interface ProductSectionInterface
{
    /**
     * Get Products
     * @param string $section
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getProductSection($section);

    /**
     * Get Promo Products
     * @param string $promoType
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPromoProducts($promoType);   
}