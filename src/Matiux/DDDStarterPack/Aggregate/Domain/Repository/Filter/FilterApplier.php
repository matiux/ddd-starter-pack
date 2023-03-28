<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

/**
 * @template T
 */
interface FilterApplier
{
    /**
     * @param T                      $target
     * @param FilterAppliersRegistry $appliersRegistry
     */
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void;

    public function supports(FilterAppliersRegistry $appliersRegistry): bool;
}
