<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SQS\Configuration;

use DDDStarterPack\Message\Driver\AWS\Configuration\AWSConfiguration;

class SQSConfiguration extends AWSConfiguration
{
    public function __construct(
        private string $region,
        private string|null $accessKey = null,
        private string|null $secretKey = null,
        private string|null $queueUrl = null,
    ) {
        parent::__construct($this->region, $this->accessKey, $this->secretKey);
    }

    public function queueUrl(): string|null
    {
        return $this->queueUrl;
    }
}
