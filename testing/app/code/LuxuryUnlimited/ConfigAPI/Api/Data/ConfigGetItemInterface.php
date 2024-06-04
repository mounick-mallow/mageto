<?php

namespace LuxuryUnlimited\ConfigAPI\Api\Data;

/**
 * Interface ConfigGetItemInterface
 * @api
 *
 */
interface ConfigGetItemInterface
{
    /**
     * Get configuration path
     *
     * @return string
     */
    public function getPath();
}
