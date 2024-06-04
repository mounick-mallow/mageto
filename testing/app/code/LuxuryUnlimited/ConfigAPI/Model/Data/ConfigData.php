<?php
declare(strict_types=1);

namespace LuxuryUnlimited\ConfigAPI\Model\Data;

use LuxuryUnlimited\ConfigAPI\Api\Data\ConfigDataInterface;

/**
 * Set Config data Class
 * Class ConfigData
 */
class ConfigData implements ConfigDataInterface
{
    /**
     * Config path
     *
     * @var string
     */
    public $path;

    /**
     * Config value
     *
     * @var null|string
     */
    public $value;

    /**
     * Get config path
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Set config path
     *
     * @param string $path Config path
     *
     * @return \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigDataInterface
     */
    public function setPath(string $path): ConfigDataInterface
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get config value
     *
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * Set config value
     *
     * @param string|null $value Config value
     *
     * @return \LuxuryUnlimited\ConfigAPI\Api\Data\ConfigDataInterface
     */
    public function setValue(?string $value): ConfigDataInterface
    {
        $this->value = $value;
        return $this;
    }
}
