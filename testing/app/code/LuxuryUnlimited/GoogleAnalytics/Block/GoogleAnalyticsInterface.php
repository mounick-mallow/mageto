<?php

namespace LuxuryUnlimited\GoogleAnalytics\Block;

/**
 * Interface GoogleAnalytics
 *
 * @api
 */
interface GoogleAnalyticsInterface
{
    /**
     * Google Analytics Data.
     *
     * @return array
     */
    public function getGoogleAnalyticsScriptData(): array;
}
