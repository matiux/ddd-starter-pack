<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SNS\Configuration;

use DDDStarterPack\Message\Driver\AWS\Configuration\AWSConfiguration;

class SNSConfiguration extends AWSConfiguration
{
    public function __construct(
        private string $region,
        private string|null $accessKey = null,
        private string|null $secretKey = null,
        private string|null $topicArn = null,
    ) {
        parent::__construct($this->region, $this->accessKey, $this->secretKey);
    }

    public function topicArn(): string|null
    {
        return $this->topicArn;
    }
}
