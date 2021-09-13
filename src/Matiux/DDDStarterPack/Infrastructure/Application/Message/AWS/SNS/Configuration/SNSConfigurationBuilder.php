<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS\SNS\Configuration;

use DDDStarterPack\Infrastructure\Application\Message\AWS\Configuration\AWSConfigurationBuilder;

class SNSConfigurationBuilder extends AWSConfigurationBuilder
{
    public static function create(string $driverName = 'SNS'): static
    {
        return parent::create($driverName);
    }

    public function withTopicArn(string $topicArn): self
    {
        $this->configs['sns_topic_arn'] = $topicArn;

        return $this;
    }
}
