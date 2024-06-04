<?php
declare(strict_types=1);

namespace LuxuryUnlimited\ConfigAPI\Api\Data;

/**
 * Interface ConfigDataInterface
 */
interface ConfigDataInterface
{
    /**
     * Get config path
     *
     * @return string
     */
    public function getPath(): string;

    /**
     * Set config path
     *
     * @param string $path Config path
     *
     * @return self
     */
    public function setPath(string $path): self;

    /**
     * Get config value
     *
     * @return string|null
     */
    public function getValue(): ?string;

    /**
     * Set config value
     *
     * @param string|null $value Config value
     *
     * @return self
     */
    public function setValue(?string $value): self;
}
