<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration;

use DDDStarterPack\Application\Message\Configuration\ConfigurationParamConstraint;

class QueueNameIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'queue_url';

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
