<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\Configuration;

use DDDStarterPack\Message\Infrastructure\Configuration\ConfigurationParamRegistry;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\Configuration\AccessKeyIsValidConfigurationParamConstraint;
use PHPUnit\Framework\TestCase;

class ConfigurationParamRegistryTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_resolve_constraint(): void
    {
        $registry = new ConfigurationParamRegistry();

        $registry->addConstraint(new AccessKeyIsValidConfigurationParamConstraint());

        $constraint = $registry->resolve('access_key');

        self::assertNotNull($constraint);
        self::assertInstanceOf(AccessKeyIsValidConfigurationParamConstraint::class, $constraint);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_constraint_is_existent(): void
    {
        self::expectException(\RuntimeException::class);

        $registry = new ConfigurationParamRegistry();

        $registry->addConstraint(new AccessKeyIsValidConfigurationParamConstraint());
        $registry->addConstraint(new AccessKeyIsValidConfigurationParamConstraint());
    }
}
