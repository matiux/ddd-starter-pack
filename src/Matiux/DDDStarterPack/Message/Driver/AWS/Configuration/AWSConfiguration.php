<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\Configuration;

abstract class AWSConfiguration
{
    public function __construct(
        private string $region,
        private null|string $accessKey = null,
        private null|string $secretKey = null,
    ) {
    }

    public function region(): string
    {
        return $this->region;
    }

    public function accessKey(): null|string
    {
        return $this->accessKey;
    }

    public function secretKey(): null|string
    {
        return $this->secretKey;
    }
}
