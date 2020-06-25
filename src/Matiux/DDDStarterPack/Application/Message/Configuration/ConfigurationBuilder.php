<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message\Configuration;

abstract class ConfigurationBuilder
{
    /** @var string */
    protected $driverName = '';

    /** @var array<string, int|string> */
    protected $configs = [];

    /**
     * @param string $driverName
     *
     * @return static
     */
    abstract public static function create(string $driverName = '');

    public function build(): Configuration
    {
        return Configuration::withParams($this->driverName, $this->configs);
    }
}
