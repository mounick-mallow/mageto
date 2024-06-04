<?php

namespace LuxuryUnlimited\ConfigAPI\Api\Data;

/**
 * Interface ConfigItemInterface
 *
 * @api
 */
interface ConfigItemInterface
{
    /**
     * Get configuration path
     *
     * @return string
     */
    public function getPath();

    /**
     * Get configuration value
     *
     * @return mixed
     */
    public function getValue();
}
