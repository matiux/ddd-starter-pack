<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\AWS\SNS\Configuration;

use DDDStarterPack\Message\Application\Configuration\ConfigurationParamConstraint;

class TopicArnIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'sns_topic_arn';

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
