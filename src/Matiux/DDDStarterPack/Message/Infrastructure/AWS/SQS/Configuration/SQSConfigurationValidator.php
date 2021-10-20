<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\AWS\SQS\Configuration;

use DDDStarterPack\Message\Infrastructure\AWS\Configuration\AWSConfigurationValidator;

class SQSConfigurationValidator extends AWSConfigurationValidator
{
    protected function buildRegistry(): void
    {
        parent::buildRegistry();

        $this->configurationParamRegistry->addConstraint(new QueueNameIsValidConfigurationParamConstraint());
    }
}
