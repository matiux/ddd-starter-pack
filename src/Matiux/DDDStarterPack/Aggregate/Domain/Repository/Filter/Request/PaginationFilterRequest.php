<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter\Request;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\PaginationKeyFilterApplier;

trait PaginationFilterRequest
{
    private array $paginationFilters = [
        'page' => 1,
        'per_page' => -1,
    ];

    public function withPagination(null|int $page, null|int $perPage): self
    {
        if ($page && $perPage) {
            $this->paginationFilters[PaginationKeyFilterApplier::PAGE] = $page;
            $this->paginationFilters[PaginationKeyFilterApplier::PER_PAGE] = $perPage;
        }

        return $this;
    }

    protected function getPaginationFilters(): array
    {
        return $this->paginationFilters;
    }
}
