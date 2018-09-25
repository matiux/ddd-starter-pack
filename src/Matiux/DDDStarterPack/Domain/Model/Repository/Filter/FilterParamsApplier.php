<?php

namespace DDDStarterPack\Domain\Model\Repository\Filter;

interface FilterParamsApplier
{
    public function key(): string;

    public function apply($target, FilterParams $filterParams): void;

    public function supports(): bool;
}
