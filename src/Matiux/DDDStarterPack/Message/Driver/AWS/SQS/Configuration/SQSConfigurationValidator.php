<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SQS\Configuration;

use DDDStarterPack\Message\Driver\AWS\Configuration\AWSConfigurationValidator;

class SQSConfigurationValidator extends AWSConfigurationValidator
{
    #[\Override]
    protected function buildRegistry(): void
    {
        parent::buildRegistry();

        $this->configurationParamRegistry->addConstraint(new QueueNameIsValidConfigurationParamConstraint());
    }
}
