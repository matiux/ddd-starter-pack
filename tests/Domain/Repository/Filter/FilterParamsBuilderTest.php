<?php

namespace Tests\DDDStarterPack\Domain\Repository\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsApplier;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsBuilder;
use PHPUnit\Framework\TestCase;

class FilterParamsBuilderTest extends TestCase
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

        $filterParams->applyTo($target);

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

class DummyArrayTarget
{
    private $data = [];

    public function add($item)
    {
        $this->data[] = $item;
    }

    public function get(): array
    {
        return $this->data;
    }
}

class DummyFilterParamsApplier implements FilterParamsApplier
{
    private $key;

    public function __construct(string $key)
    {
        if (!$key) {
            throw new \InvalidArgumentException('the given key is empty');
        }

        $this->key = $key;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function apply($target, FilterParams $filterParams): void
    {
        $this->doApply($target, $filterParams);
    }

    protected function doApply(DummyArrayTarget $target, FilterParams $filterParams)
    {
        $target->add([
            $this->key() => $filterParams->get($this->key())
        ]);
    }

    public function supports(FilterParams $filterParams): bool
    {
        return true;
    }
}
