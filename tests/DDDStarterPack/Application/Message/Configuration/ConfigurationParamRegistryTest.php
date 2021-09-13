<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Application\Message\Configuration;

use DDDStarterPack\Application\Message\Configuration\ConfigurationParamRegistry;
use DDDStarterPack\Infrastructure\Application\Message\AWS\Configuration\AccessKeyIsValidConfigurationParamConstraint;
use PHPUnit\Framework\TestCase;

class ConfigurationParamRegistryTest extends TestCase
{
    /**
     * @test
     */
    public function registry_should_resolve_constraint(): void
    {
        $registry = new ConfigurationParamRegistry();

        $registry->addConstraint(new AccessKeyIsValidConfigurationParamConstraint());

        $constraint = $registry->resolve('access_key');

        self::assertNotNull($constraint);
        self::assertInstanceOf(AccessKeyIsValidConfigurationParamConstraint::class, $constraint);
    }
}
