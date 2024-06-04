<?php

namespace LuxuryUnlimited\ConfigAPI\Model\Data;

use LuxuryUnlimited\ConfigAPI\Api\Data\ConfigItemInterface;

class ConfigItem implements ConfigItemInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var mixed
     */
    private $value;

    /**
     * ConfigItem constructor.
     *
     * @param string $path
     * @param mixed  $value
     */
    public function __construct($path, $value)
    {
        $this->path = $path;
        $this->value = $value;
    }

    /**
     * Get configuration path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get configuration value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'path' => $this->getPath(),
            'value' => $this->getValue()
        ];

        return $data;
    }
}
