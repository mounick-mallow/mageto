<?php
/**
 * LuxuryUnlimited_HomeApi
 *
 * @copyright   Copyright (c) 2023
 */
namespace LuxuryUnlimited\HomeApi\Api;

interface CategorySectionInterface
{
    /**
     * Get Categories section
     * @param string $section
     * @return mixed
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategorySection($section);
}