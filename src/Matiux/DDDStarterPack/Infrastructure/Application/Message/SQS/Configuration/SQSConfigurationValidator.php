<?php

namespace DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

use DDDStarterPack\Application\Message\Configuration\ConfigurationValidator;

class SQSConfigurationValidator extends ConfigurationValidator
{
    protected function buildRegistry()
    {
        $this->configurationParamRegistry->addConstraint(new AccessKeyIsValidConfigurationParamConstraint());
        $this->configurationParamRegistry->addConstraint(new BucketNameIsValidConfigurationParamConstraint());
        $this->configurationParamRegistry->addConstraint(new RegionIsValidConfigurationParamConstraint());
        $this->configurationParamRegistry->addConstraint(new SecretKeyIsValidConfigurationParamConstraint());
    }
}
