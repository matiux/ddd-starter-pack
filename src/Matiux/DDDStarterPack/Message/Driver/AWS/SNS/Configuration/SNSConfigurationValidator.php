<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SNS\Configuration;

use DDDStarterPack\Message\Driver\AWS\Configuration\AWSConfigurationValidator;

class SNSConfigurationValidator extends AWSConfigurationValidator
{
    protected function buildRegistry(): void
    {
        parent::buildRegistry();

        $this->configurationParamRegistry->addConstraint(new TopicArnIsValidConfigurationParamConstraint());
    }
}
