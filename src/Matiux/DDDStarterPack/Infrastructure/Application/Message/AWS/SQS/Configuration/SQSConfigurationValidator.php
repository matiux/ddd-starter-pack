<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration;

use DDDStarterPack\Infrastructure\Application\Message\AWS\Configuration\AWSConfigurationValidator;

class SQSConfigurationValidator extends AWSConfigurationValidator
{
    protected function buildRegistry(): void
    {
        parent::buildRegistry();

        $this->configurationParamRegistry->addConstraint(new QueueNameIsValidConfigurationParamConstraint());
    }
}
