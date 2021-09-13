<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS\SNS\Configuration;

use DDDStarterPack\Infrastructure\Application\Message\AWS\Configuration\AWSConfigurationValidator;

class SNSConfigurationValidator extends AWSConfigurationValidator
{
    protected function buildRegistry(): void
    {
        parent::buildRegistry();

        $this->configurationParamRegistry->addConstraint(new TopicArnIsValidConfigurationParamConstraint());
    }
}
