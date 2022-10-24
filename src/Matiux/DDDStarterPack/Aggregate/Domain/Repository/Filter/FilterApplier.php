<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

/**
 * @template T
 */
interface FilterApplier
{
    public function key(): string;

    /**
     * @param T                     $target
     * @param FilterApplierRegistry $filterApplierRegistry
     */
    public function apply($target, FilterApplierRegistry $filterApplierRegistry): void;

    public function supports(FilterApplierRegistry $filterApplierRegistry): bool;
}
