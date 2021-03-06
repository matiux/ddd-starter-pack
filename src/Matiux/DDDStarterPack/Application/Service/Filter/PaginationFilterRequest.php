<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\PaginationFilterParamsApplierKeys;

trait PaginationFilterRequest
{
    private $paginationFilters = [
        'page' => 1,
        'per_page' => -1,
    ];

    public function withPagination(?int $page, ?int $perPage): self
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
