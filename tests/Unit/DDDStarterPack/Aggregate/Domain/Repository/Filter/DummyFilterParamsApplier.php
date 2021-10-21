<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParams;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParamsApplier;
use InvalidArgumentException;

/**
 * @implements FilterParamsApplier<DummyArrayTarget>
 */
class DummyFilterParamsApplier implements FilterParamsApplier
{
    private string $key;

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
            $this->key() => $filterParams->getFilterValueForKey($this->key()),
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
