<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Driver\AWS\SQS\Configuration;

use DDDStarterPack\Message\Configuration\Configuration;
use DDDStarterPack\Message\Driver\AWS\SQS\Configuration\QueueNameIsValidConfigurationParamConstraint;
use DDDStarterPack\Message\Driver\AWS\SQS\Configuration\SQSConfigurationBuilder;
use PHPUnit\Framework\TestCase;

class QueueNameIsValidConfigurationParamConstraintTest extends TestCase
{
    /**
     * @test
     */
    public function queue_name_must_be_valid(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withQueueUrl('queue_url')
            ->build();

        $constraint = new QueueNameIsValidConfigurationParamConstraint();

        self::assertTrue($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function queue_name_cannot_be_empty(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withQueueUrl('')
            ->build();

        $constraint = new QueueNameIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function queue_name_must_be_exist(): void
    {
        $configuration = Configuration::withParams('Foo', []);

        $constraint = new QueueNameIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }
}
