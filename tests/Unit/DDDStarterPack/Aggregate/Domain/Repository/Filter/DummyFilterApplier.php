<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplier;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplierRegistry;
use InvalidArgumentException;

/**
 * @implements FilterApplier<DummyArrayTarget>
 */
class DummyFilterApplier implements FilterApplier
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
     * @param DummyArrayTarget      $target
     * @param FilterApplierRegistry $filterApplierRegistry
     */
    public function apply($target, FilterApplierRegistry $filterApplierRegistry): void
    {
        $target->add([
            $this->key() => $filterApplierRegistry->getFilterValueForKey($this->key()),
        ]);
    }

    public function key(): string
    {
        return $this->key;
    }

    public function supports(FilterApplierRegistry $filterApplierRegistry): bool
    {
        return true;
    }
}
