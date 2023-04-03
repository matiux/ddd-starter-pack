<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\PaginationKeyFilterApplier;

class DoctrineGenericPaginationApplier extends DoctrineFilterApplier
{
    protected function pageKey(): string
    {
        return PaginationKeyFilterApplier::PAGE;
    }

    protected function perPageKey(): string
    {
        return PaginationKeyFilterApplier::PER_PAGE;
    }

    /**
     * {@inheritDoc}
     */
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        /** @var int $page */
        $page = $appliersRegistry->getFilterValueForKey($this->pageKey(), '1');

        /** @var int $perPage */
        $perPage = $appliersRegistry->getFilterValueForKey($this->perPageKey());

        if (-1 !== $perPage) {
            $offset = ($page - 1) * $perPage;
            $target->setFirstResult($offset);

            $limit = $perPage;
            $target->setMaxResults($limit);
        }
    }

    public function supports(FilterAppliersRegistry $appliersRegistry): bool
    {
        return
            (
                $appliersRegistry->hasFilterWithKey(PaginationKeyFilterApplier::PAGE)
                || $appliersRegistry->hasFilterWithKey(PaginationKeyFilterApplier::PER_PAGE)
            )
            && (int) $appliersRegistry->getFilterValueForKey(PaginationKeyFilterApplier::PER_PAGE) > 0
            && (int) $appliersRegistry->getFilterValueForKey(PaginationKeyFilterApplier::PAGE) > 0;
    }
}
