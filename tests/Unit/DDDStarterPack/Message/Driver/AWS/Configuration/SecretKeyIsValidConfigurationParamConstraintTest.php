<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Driver\AWS\Configuration;

use DDDStarterPack\Message\Configuration\Configuration;
use DDDStarterPack\Message\Driver\AWS\Configuration\SecretKeyIsValidConfigurationParamConstraint;
use DDDStarterPack\Message\Driver\AWS\SQS\Configuration\SQSConfigurationBuilder;
use PHPUnit\Framework\TestCase;

class SecretKeyIsValidConfigurationParamConstraintTest extends TestCase
{
    /**
     * @test
     */
    public function secret_key_must_be_valid(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withSecretKey('<secret>')
            ->build();

        $constraint = new SecretKeyIsValidConfigurationParamConstraint();

        self::assertTrue($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function secret_key_cannot_be_empty(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withSecretKey('')
            ->build();

        $constraint = new SecretKeyIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function secret_key_must_be_exist(): void
    {
        $configuration = Configuration::withParams('Foo', []);

        $constraint = new SecretKeyIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }
}
