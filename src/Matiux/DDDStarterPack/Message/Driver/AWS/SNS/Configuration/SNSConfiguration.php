<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SNS\Configuration;

use DDDStarterPack\Message\Driver\AWS\Configuration\AWSConfiguration;

class SNSConfiguration extends AWSConfiguration
{
    public function __construct(
        private string $region,
        private null|string $accessKey = null,
        private null|string $secretKey = null,
        private null|string $topicArn = null,
    ) {
        parent::__construct($this->region, $this->accessKey, $this->secretKey);
    }

    public function topicArn(): null|string
    {
        return $this->topicArn;
    }
}
