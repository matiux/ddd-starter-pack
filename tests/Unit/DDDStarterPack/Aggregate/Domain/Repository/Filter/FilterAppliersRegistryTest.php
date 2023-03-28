<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;
use PHPUnit\Framework\TestCase;

class FilterAppliersRegistryTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_throw_exception_if_key_does_not_exist(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage("Filter with key 'surname' does not exist");

        $requestedFilters = [
            'name' => 'Matteo',
            'skills' => ['architecture', 'programming'],
        ];

        $appliersRegistry = new FilterAppliersRegistry([], $requestedFilters);
        $appliersRegistry->getFilterValueForKey('surname');
    }

    /**
     * @test
     */
    public function it_should_verify_if_key_exists(): void
    {
        $requestedFilters = [
            'name' => 'Matteo',
        ];

        $appliersRegistry = new FilterAppliersRegistry([], $requestedFilters);

        self::assertFalse($appliersRegistry->hasFilterWithKey('surname'));
    }
}
