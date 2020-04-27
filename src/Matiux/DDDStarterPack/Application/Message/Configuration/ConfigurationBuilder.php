<?php

namespace DDDStarterPack\Application\Message\Configuration;

abstract class ConfigurationBuilder
{
    protected $driverName = '';

    abstract public static function create(string $driverName = ''): ConfigurationBuilder;

    abstract public function build(): Configuration;
}
