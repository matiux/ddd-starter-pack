<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

/**
 * @template T
 */
interface FilterApplier
{
    public function supported(): string;

    /**
     * @param T              $target
     * @param FilterAppliersRegistry $filterAppliers
     */
    public function apply($target, FilterAppliersRegistry $filterAppliers): void;

    public function supports(FilterAppliersRegistry $filterAppliers): bool;
}
