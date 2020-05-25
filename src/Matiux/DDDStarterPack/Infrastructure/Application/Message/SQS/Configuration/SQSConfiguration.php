<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

class SQSConfiguration
{
    private $queue;
    private $region;
    private $accessKey;
    private $secretKey;

    public function __construct(string $queue, string $region, string $accessKey = '', string $secretKey = '')
    {
        $this->queue = $queue;
        $this->region = $region;
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
    }

    public function queue(): string
    {
        return $this->queue;
    }

    public function region(): string
    {
        return $this->region;
    }

    public function accessKey(): string
    {
        return $this->accessKey;
    }

    public function secretKey(): string
    {
        return $this->secretKey;
    }
}
