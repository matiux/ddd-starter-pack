<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\Driver\AWS\Configuration;

use DDDStarterPack\Message\Infrastructure\Configuration\Configuration;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\Configuration\AccessKeyIsValidConfigurationParamConstraint;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS\Configuration\SQSConfigurationBuilder;
use PHPUnit\Framework\TestCase;

class AccessKeyIsValidConfigurationParamConstraintTest extends TestCase
{
    /**
     * @test
     */
    public function access_key_must_be_valid(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withAccessKey('access_key')
            ->build();

        $constraint = new AccessKeyIsValidConfigurationParamConstraint();

        self::assertTrue($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function access_key_cannot_be_empty(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withAccessKey('')
            ->build();

        $constraint = new AccessKeyIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function access_key_must_be_exist(): void
    {
        $configuration = Configuration::withParams('Foo', []);

        $constraint = new AccessKeyIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }
}
