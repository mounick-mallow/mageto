<?php
/**
 * LuxuryUnlimited_HomeApi
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\HomeApi\Api;

interface BrandSectionInterface
{
    /**
     * Get Brand section
     *
     * @param string $section
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBrandSection($section);
}
