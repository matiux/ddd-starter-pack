<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\Configuration;

use DDDStarterPack\Message\Configuration\ConfigurationParamConstraint;

class RegionIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'region';

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
