<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Application\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\PaginationFilterParamsApplierKeys;

trait PaginationFilterRequest
{
    private array $paginationFilters = [
        'page' => 1,
        'per_page' => -1,
    ];

    public function withPagination(null|int $page, null|int $perPage): self
    {
        if ($page && $perPage) {
            $this->paginationFilters[PaginationFilterParamsApplierKeys::PAGE] = $page;
            $this->paginationFilters[PaginationFilterParamsApplierKeys::PER_PAGE] = $perPage;
        }

        return $this;
    }

    protected function getPaginationFilters(): array
    {
        return $this->paginationFilters;
    }
}
