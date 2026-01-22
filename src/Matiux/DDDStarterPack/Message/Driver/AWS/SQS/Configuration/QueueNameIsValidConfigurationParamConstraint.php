<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SQS\Configuration;

use DDDStarterPack\Message\Configuration\ConfigurationParamConstraint;
use Override;

class QueueNameIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'queue_url';

    #[\Override]
    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
