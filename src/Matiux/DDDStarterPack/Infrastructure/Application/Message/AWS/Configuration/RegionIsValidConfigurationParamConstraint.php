<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS\Configuration;

use DDDStarterPack\Application\Message\Configuration\ConfigurationParamConstraint;

class RegionIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'region';

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
