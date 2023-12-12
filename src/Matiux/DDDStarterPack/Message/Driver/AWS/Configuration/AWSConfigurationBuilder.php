<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\Configuration;

use DDDStarterPack\Message\Configuration\ConfigurationBuilder;

abstract class AWSConfigurationBuilder extends ConfigurationBuilder
{
    final public function __construct() {}

    /**
     * @param string $driverName
     *
     * @return static
     */
    public static function create(string $driverName): static
    {
        $builder = new static();

        $builder->driverName = $driverName;

        return $builder;
    }

    public function withRegion(string $region): static
    {
        $this->configs['region'] = $region;

        return $this;
    }

    public function withAccessKey(string $accessKey): static
    {
        $this->configs['access_key'] = $accessKey;

        return $this;
    }

    public function withSecretKey(string $secretKey): static
    {
        $this->configs['secret_key'] = $secretKey;

        return $this;
    }
}
