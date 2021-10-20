<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\AWS\Configuration;

use DDDStarterPack\Message\Application\Configuration\ConfigurationParamConstraint;

class RegionIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'region';

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
