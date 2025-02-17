<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Filter\Request;

use DDDStarterPack\Repository\Filter\SortingKeyFilterApplier;

trait SortingFilterRequest
{
    private array $sortingFilters = [];

    public function withSorting(null|string $sortingField, null|string $sortingDirection): static
    {
        if ($sortingField && $sortingDirection) {
            $this->sortingFilters[SortingKeyFilterApplier::SORT] = $sortingField;
            $this->sortingFilters[SortingKeyFilterApplier::SORT_DIRECTION] = $sortingDirection;
        }

        return $this;
    }

    protected function getSortingFilters(): array
    {
        return $this->sortingFilters;
    }
}
