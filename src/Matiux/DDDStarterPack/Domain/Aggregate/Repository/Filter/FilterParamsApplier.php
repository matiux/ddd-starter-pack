<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate\Repository\Filter;

/**
 * @template T
 */
interface FilterParamsApplier
{
    public function key(): string;

    /**
     * @param T            $target
     * @param FilterParams $filterParams
     */
    public function apply($target, FilterParams $filterParams): void;

    public function supports(FilterParams $filterParams): bool;
}
