<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

use DDDStarterPack\Application\Message\Configuration\ConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageService;

class SQSConfigurationBuilder extends ConfigurationBuilder
{
    /**
     * @param string $driverName
     *
     * @return static
     */
    public static function create(string $driverName = null)
    {
        $builder = new static();

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

    public function withQueue(string $queueName): self
    {
        $this->configs['queue_name'] = $queueName;

        return $this;
    }
}
