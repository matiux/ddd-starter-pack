<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\Filter;

interface FilterParamsApplier
{
    public function key(): string;

    public function apply($target, FilterParams $filterParams): void;

    public function supports(FilterParams $filterParams): bool;
}
