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
     * @param T              $target
     * @param FilterAppliers $filterAppliers
     */
    public function apply($target, FilterAppliers $filterAppliers): void;

    public function supports(FilterAppliers $filterAppliers): bool;
}
