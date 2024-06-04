<?php

namespace LuxuryUnlimited\ConfigAPI\Model\Data;

use LuxuryUnlimited\ConfigAPI\Api\Data\ConfigGetItemInterface;

class ConfigGetItem implements ConfigGetItemInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * ConfigItem constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
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
     * Convert the object to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [
            'path' => $this->getPath(),
        ];

        return $data;
    }
}
