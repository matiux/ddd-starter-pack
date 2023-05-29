<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Filter;

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
