<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistryBuilder;
use PHPUnit\Framework\TestCase;

class FilterAppliersRegistryBuilderTest extends TestCase
{
    /**
     * @test
     *
     * @group filter
     */
    public function it_should_create_a_filterparams_by_builder(): void
    {
        $registryBuilder = new FilterAppliersRegistryBuilder(
            [
                new DummyFilterApplier('name'),
                new DummyFilterApplier('skills'),
            ],
        );

        // Psalm gets angry - as it should be
        // $registryBuilder->addApplier(new \stdClass());

        $filterAppliersRegistry = $registryBuilder->build([
            'name' => 'Matteo',
            'skills' => ['architecture', 'programming'],
        ]);

        $target = new DummyArrayTarget();

        $filterAppliersRegistry->applyToTarget($target);

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

        $registryBuilder = new FilterAppliersRegistryBuilder();
        $registryBuilder->addApplier(new DummyFilterApplier('name'));

        $registryBuilder->build([
            'name' => 'Matteo',
            'skills' => ['architecture', 'programming'],
        ]);

        $registryBuilder->addApplier(new DummyFilterApplier('skills'));
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
