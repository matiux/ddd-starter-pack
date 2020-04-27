<?php

namespace DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\Configuration\ConfigurationParamConstraint;

class SecretKeyIsValidConfigurationParamConstraint extends ConfigurationParamConstraint
{
    private const PARAM_NAME = 'secret_key';

    public function isSatisfiedBy(Configuration $configuration): bool
    {
        $configs = $configuration->getParams();

        return array_key_exists(self::PARAM_NAME, $configs) && !empty(trim($configs[self::PARAM_NAME]));
    }

    public function name(): string
    {
        return self::PARAM_NAME;
    }
}
