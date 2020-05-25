<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

use DDDStarterPack\Application\Message\Configuration\ConfigurationValidator;

class SQSConfigurationValidator extends ConfigurationValidator
{
    protected function buildRegistry(): void
    {
        $this->configurationParamRegistry->addConstraint(new AccessKeyIsValidConfigurationParamConstraint());
        $this->configurationParamRegistry->addConstraint(new QueueNameIsValidConfigurationParamConstraint());
        $this->configurationParamRegistry->addConstraint(new RegionIsValidConfigurationParamConstraint());
        $this->configurationParamRegistry->addConstraint(new SecretKeyIsValidConfigurationParamConstraint());
    }
}
