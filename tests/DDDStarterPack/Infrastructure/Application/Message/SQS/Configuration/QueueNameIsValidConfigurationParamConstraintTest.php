<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\QueueNameIsValidConfigurationParamConstraint;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
use PHPUnit\Framework\TestCase;

class QueueNameIsValidConfigurationParamConstraintTest extends TestCase
{
    /**
     * @test
     */
    public function queue_name_must_be_valid(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withQueue('queue_name')
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
            ->withQueue('')
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
