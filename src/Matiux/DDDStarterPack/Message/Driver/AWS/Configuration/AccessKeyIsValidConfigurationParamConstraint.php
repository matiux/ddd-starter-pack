<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\Configuration;

use DDDStarterPack\Message\Configuration\ConfigurationParamConstraint;

class AccessKeyIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'access_key';

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
