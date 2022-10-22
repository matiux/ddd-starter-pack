<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\AWS\SNS\Configuration;

use DDDStarterPack\Message\Infrastructure\Driver\AWS\Configuration\AWSConfigurationValidator;

class SNSConfigurationValidator extends AWSConfigurationValidator
{
    protected function buildRegistry(): void
    {
        parent::buildRegistry();

        $this->configurationParamRegistry->addConstraint(new TopicArnIsValidConfigurationParamConstraint());
    }
}