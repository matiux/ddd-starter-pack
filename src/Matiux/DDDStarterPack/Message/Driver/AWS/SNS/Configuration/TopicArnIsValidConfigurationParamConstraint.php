<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SNS\Configuration;

use DDDStarterPack\Message\Configuration\ConfigurationParamConstraint;

class TopicArnIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'sns_topic_arn';

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
