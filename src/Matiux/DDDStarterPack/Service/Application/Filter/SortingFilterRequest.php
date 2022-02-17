<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Application\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\SortingFilterParamsApplierKeys;

trait SortingFilterRequest
{
    private array $sortingFilters = [];

    public function withSorting(null|string $sortingField, null|string $sortingDirection): self
    {
        if ($sortingField && $sortingDirection) {
            $this->sortingFilters[SortingFilterParamsApplierKeys::SORT] = $sortingField;
            $this->sortingFilters[SortingFilterParamsApplierKeys::SORT_DIRECTION] = $sortingDirection;
        }

        return $this;
    }

    protected function getSortingFilters(): array
    {
        return $this->sortingFilters;
    }
}
