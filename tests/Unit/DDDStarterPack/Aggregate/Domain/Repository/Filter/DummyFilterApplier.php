<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplier;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;

/**
 * @implements FilterApplier<DummyArrayTarget>
 */
class DummyFilterApplier implements FilterApplier
{
    private string $key;

    public function __construct(string $key)
    {
        if (!$key) {
            throw new \InvalidArgumentException('the given key is empty');
        }

        $this->key = $key;
    }

    /**
     * @param DummyArrayTarget       $target
     * @param FilterAppliersRegistry $filterAppliers
     */
    public function apply($target, FilterAppliersRegistry $filterAppliers): void
    {
        $target->add([
            $this->key => $filterAppliers->getFilterValueForKey($this->key),
        ]);
    }

    public function supports(FilterAppliersRegistry $filterAppliers): bool
    {
        return true;
    }
}
