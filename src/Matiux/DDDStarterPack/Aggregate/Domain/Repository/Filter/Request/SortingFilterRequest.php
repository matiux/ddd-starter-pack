<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter\Request;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\SortingKeyFilterApplier;

trait SortingFilterRequest
{
    private array $sortingFilters = [];

    public function withSorting(null|string $sortingField, null|string $sortingDirection): self
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
