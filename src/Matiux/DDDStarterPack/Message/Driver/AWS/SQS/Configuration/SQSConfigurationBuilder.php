<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SQS\Configuration;

use DDDStarterPack\Message\Driver\AWS\Configuration\AWSConfigurationBuilder;

class SQSConfigurationBuilder extends AWSConfigurationBuilder
{
    public static function create(string $driverName = 'SQS'): static
    {
        return parent::create($driverName);
    }

    public function withQueueUrl(string $queueUrl): static
    {
        $this->configs['queue_url'] = $queueUrl;

        return $this;
    }
}
