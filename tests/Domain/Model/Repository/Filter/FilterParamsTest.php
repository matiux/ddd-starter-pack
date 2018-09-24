<?php

namespace Tests\DDDStarterPack\Domain\Model\Repository\Filter;

use DDDStarterPack\Domain\Model\Repository\Filter\FilterParamsBuilder;
use PHPUnit\Framework\TestCase;
use Tests\DDDStarterPack\Support\Domain\Model\Repository\Filter\DummyArrayTarget;
use Tests\DDDStarterPack\Support\Domain\Model\Repository\Filter\DummyFilterParamsApplier;

class FilterParamsTest extends TestCase
{
    /**
     * @test
     * @group filter
     */
    public function filter_params_can_be_created()
    {
        $filterParamsBuilder = new FilterParamsBuilder();
        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('a_key'));
        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('another_key'));

        $filterParams = $filterParamsBuilder->build([
            'a_key' => 'a_value',
            'another_key' => ['a_value', 'another_value'],
        ]);

        $target = new DummyArrayTarget();

        $filterParams->apply($target);

        $this->assertEquals([
            ['a_key' => 'a_value'],
            ['another_key' => ['a_value', 'another_value']],
        ], $target->get());
    }

    /**
     * @test
     * @group filter
     * @expectedException \LogicException
     * @expectedExceptionMessage The builder is frozen
     */
    public function filter_params_builder_thow_an_exception()
    {
        $filterParamsBuilder = new FilterParamsBuilder();
        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('a_key'));

        $filterParams = $filterParamsBuilder->build([
            'a_key' => 'a_value',
            'another_key' => ['a_value', 'another_value'],
        ]);


        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('another_key'));
    }
}
