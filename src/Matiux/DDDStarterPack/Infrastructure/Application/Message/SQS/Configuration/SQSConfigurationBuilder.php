<?php

namespace DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageService;

class SQSConfigurationBuilder
{
    private $driverName;
    private $configs = [];

    public static function create(string $driverName = null): self
    {
        $builder = new self;

        $builder->driverName = $driverName ?? SQSMessageService::NAME;

        return $builder;
    }

    public function withRegion(string $region): self
    {
        $this->configs['region'] = $region;

        return $this;
    }

    public function withAccessKey(string $accessKey): self
    {
        $this->configs['access_key'] = $accessKey;

        return $this;
    }

    public function withSecretKey(string $secretKey): self
    {
        $this->configs['secret_key'] = $secretKey;

        return $this;
    }

    public function withQueue(string $bucketName): self
    {
        $this->configs['queue'] = $bucketName;

        return $this;
    }

    public function build(): Configuration
    {
        return Configuration::withParams($this->driverName, $this->configs);
    }
}
