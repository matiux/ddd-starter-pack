<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParamsBuilder;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

class FilterParamsBuilderTest extends TestCase
{
    /**
     * @test
     * @group filter
     */
    public function it_should_create_a_filterparams_by_builder(): void
    {
        $filterParamsBuilder = new FilterParamsBuilder(
            [
                new DummyFilterParamsApplier('name'),
                new DummyFilterParamsApplier('skills'),
            ]
        );

        // Psalm gets angry - as it should be
        // $filterParamsBuilder->addApplier(new \stdClass());

        $filterParams = $filterParamsBuilder->build([
            'name' => 'Matteo',
            'skills' => ['architecture', 'programming'],
        ]);

        $target = new DummyArrayTarget();

        $filterParams->applyToTarget($target);

        $this->assertEquals([
            ['name' => 'Matteo'],
            [
                'skills' => ['architecture', 'programming'],
            ],
        ], $target->get());
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_two_filter_params_applier_have_same_key(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Applier for key \'name\' is already set');

        (new FilterParamsBuilder(
            [
                new DummyFilterParamsApplier('name'),
                new DummyFilterParamsApplier('name'),
            ]
        ))->build(
            [
                'name' => 'Matteo',
                'skills' => ['architecture', 'programming'],
            ]
        );
    }

    /**
     * @test
     * @group filter
     */
    public function filter_params_builder_thow_an_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The builder is frozen');

        $filterParamsBuilder = new FilterParamsBuilder();
        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('name'));

        $filterParamsBuilder->build([
            'name' => 'Matteo',
            'skills' => ['architecture', 'programming'],
        ]);

        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('skills'));
    }
}

class DummyArrayTarget
{
    private array $data = [];

    /**
     * @param array $item
     */
    public function add($item): void
    {
        $this->data[] = $item;
    }

    public function get(): array
    {
        return $this->data;
    }
}
