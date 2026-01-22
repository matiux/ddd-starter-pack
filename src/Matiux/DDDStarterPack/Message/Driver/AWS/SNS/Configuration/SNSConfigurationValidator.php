<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SNS\Configuration;

use DDDStarterPack\Message\Driver\AWS\Configuration\AWSConfigurationValidator;
use Override;

class SNSConfigurationValidator extends AWSConfigurationValidator
{
    #[Override]
    protected function buildRegistry(): void
    {
        parent::buildRegistry();

        $this->configurationParamRegistry->addConstraint(new TopicArnIsValidConfigurationParamConstraint());
    }
}
