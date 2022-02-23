<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\Driver\AWS\Configuration;

use DDDStarterPack\Message\Infrastructure\Configuration\Configuration;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\Configuration\RegionIsValidConfigurationParamConstraint;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS\Configuration\SQSConfigurationBuilder;
use PHPUnit\Framework\TestCase;

class RegionIsValidConfigurationParamConstraintTest extends TestCase
{
    /**
     * @test
     */
    public function region_must_be_valid(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('ireland')
            ->build();

        $constraint = new RegionIsValidConfigurationParamConstraint();

        self::assertTrue($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function region_cannot_be_empty(): void
    {
        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('')
            ->build();

        $constraint = new RegionIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }

    /**
     * @test
     */
    public function region_must_be_exist(): void
    {
        $configuration = Configuration::withParams('Foo', []);

        $constraint = new RegionIsValidConfigurationParamConstraint();

        self::assertFalse($constraint->isSatisfiedBy($configuration));
    }
}
