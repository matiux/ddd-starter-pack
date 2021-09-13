<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration;

use DDDStarterPack\Infrastructure\Application\Message\AWS\Configuration\AWSConfiguration;

class SQSConfiguration extends AWSConfiguration
{
    public function __construct(
        private string $region,
        private null | string $accessKey = null,
        private null | string $secretKey = null,
        private null | string $queueUrl = null,
    ) {
        parent::__construct($this->region, $this->accessKey, $this->secretKey);
    }

    public function queueUrl(): null|string
    {
        return $this->queueUrl;
    }
}
