<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Application\Configuration;

use DDDStarterPack\Message\Application\Configuration\ConfigurationParamRegistry;
use DDDStarterPack\Message\Infrastructure\AWS\Configuration\AccessKeyIsValidConfigurationParamConstraint;
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
