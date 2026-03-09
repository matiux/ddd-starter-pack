<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\Configuration;

abstract class AWSConfiguration
{
    public function __construct(
        private string $region,
        private string|null $accessKey = null,
        private string|null $secretKey = null,
    ) {}

    public function region(): string
    {
        return $this->region;
    }

    public function accessKey(): string|null
    {
        return $this->accessKey;
    }

    public function secretKey(): string|null
    {
        return $this->secretKey;
    }
}
