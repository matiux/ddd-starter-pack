<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\AWS\Configuration;

use DDDStarterPack\Message\Infrastructure\Configuration\ConfigurationParamConstraint;

class AccessKeyIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'access_key';

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
