<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;
use DDDStarterPack\Repository\Filter\FilterAppliersRegistryBuilder;
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

    /**
     * @return array<array-key, array<array-key, mixed>>
     */
    public function provideValidEmptyValues(): array
    {
        return [
            'int(0)' => [0],
            'string("0")' => ['0'],
            'string("")' => [''],
            'bool(false)' => [false],
            'null' => [null],
            'empty array' => [[]],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideValidEmptyValues
     */
    public function it_should_not_fail_when_filter_applies_an_empty_value(mixed $value): void
    {
        $requestedFilters = ['some-value' => $value];
        $appliersRegistry = (new FilterAppliersRegistryBuilder())->build($requestedFilters);
        $target = new DummyArrayTarget();

        (new DummyFilterApplier('some-value'))
            ->applyTo($target, $appliersRegistry);

        self::assertSame([
            ['some-value' => $value],
        ], $target->get());
    }
}
