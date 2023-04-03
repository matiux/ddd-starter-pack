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
    public function __construct(private string $key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException('the given key is empty');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        $item = [$this->key => $appliersRegistry->getFilterValueForKey($this->key)];

        $target->add($item);
    }

    public function supports(FilterAppliersRegistry $appliersRegistry): bool
    {
        return $appliersRegistry->hasFilterWithKey($this->key);
    }
}
