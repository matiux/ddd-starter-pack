<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Domain\Repository\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsApplier;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsBuilder;
use InvalidArgumentException;
use LogicException;
use PHPUnit\Framework\TestCase;

class FilterParamsBuilderTest extends TestCase
{
    /**
     * @test
     * @group filter
     */
    public function filter_params_can_be_created(): void
    {
        $filterParamsBuilder = new FilterParamsBuilder();
        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('a_key'));
        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('another_key'));

        // Psalm gets angry - as it should be
        // $filterParamsBuilder->addApplier(new \stdClass());

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
     */
    public function filter_params_builder_thow_an_exception(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('The builder is frozen');

        $filterParamsBuilder = new FilterParamsBuilder();
        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('a_key'));

        $filterParamsBuilder->build([
            'a_key' => 'a_value',
            'another_key' => ['a_value', 'another_value'],
        ]);

        $filterParamsBuilder->addApplier(new DummyFilterParamsApplier('another_key'));
    }
}

class DummyArrayTarget
{
    /** @var array */
    private $data = [];

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

/**
 * Class DummyFilterParamsApplier.
 *
 * @implements FilterParamsApplier<DummyArrayTarget>
 */
class DummyFilterParamsApplier implements FilterParamsApplier
{
    /** @var string */
    private $key;

    public function __construct(string $key)
    {
        if (!$key) {
            throw new InvalidArgumentException('the given key is empty');
        }

        $this->key = $key;
    }

    /**
     * @param DummyArrayTarget $target
     * @param FilterParams     $filterParams
     */
    public function apply($target, FilterParams $filterParams): void
    {
        $target->add([
            $this->key() => $filterParams->get($this->key()),
        ]);
    }

    public function key(): string
    {
        return $this->key;
    }

    public function supports(FilterParams $filterParams): bool
    {
        return true;
    }
}
