<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Filter;

use PHPUnit\Framework\TestCase;
use Tests\Support\TestFilterBuilder;

class FilterParamsBuilderTest extends TestCase
{
    /**
     * @test
     *
     * @group filter
     */
    public function it_should_create_a_filterparams_by_builder(): void
    {
        $filterParamsBuilder = new TestFilterBuilder(
            [
                new DummyFilterApplier('name'),
                new DummyFilterApplier('skills'),
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
     *
     * @group filter
     */
    public function filter_params_builder_thow_an_exception(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The builder is frozen');

        $filterParamsBuilder = new TestFilterBuilder();
        $filterParamsBuilder->addApplier(new DummyFilterApplier('name'));

        $filterParamsBuilder->build([
            'name' => 'Matteo',
            'skills' => ['architecture', 'programming'],
        ]);

        $filterParamsBuilder->addApplier(new DummyFilterApplier('skills'));
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
