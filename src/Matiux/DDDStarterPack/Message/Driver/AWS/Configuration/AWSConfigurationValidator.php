<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\Configuration;

use DDDStarterPack\Message\Configuration\ConfigurationValidator;

abstract class AWSConfigurationValidator extends ConfigurationValidator
{
    protected function buildRegistry(): void
    {
        $this->configurationParamRegistry->addConstraint(new AccessKeyIsValidConfigurationParamConstraint());
        $this->configurationParamRegistry->addConstraint(new RegionIsValidConfigurationParamConstraint());
        $this->configurationParamRegistry->addConstraint(new SecretKeyIsValidConfigurationParamConstraint());
    }
}
