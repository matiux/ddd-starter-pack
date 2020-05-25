<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\SortingFilterParamsApplierKeys;

trait SortingFilterRequest
{
    private $sortingFilters = [];

    public function withSorting(?string $sortingField, ?string $sortingDirection): self
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
