<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Configuration;

abstract class ConfigurationBuilder
{
    protected string $driverName = '';

    /** @var array<string, int|string> */
    protected array $configs = [];

    abstract public static function create(string $driverName): static;

    public function build(): Configuration
    {
        return Configuration::withParams($this->driverName, $this->configs);
    }
}
