<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;
use Doctrine\ORM\QueryBuilder;

abstract class DoctrineGenericPaginationApplier extends DoctrineFilterApplier
{
    abstract protected function pageKey(): string;

    abstract protected function perPageKey(): string;

    /**
     * @psalm-param QueryBuilder $target
     *
     * @param FilterAppliersRegistry $filterAppliers
     * @param mixed                  $target
     */
    public function apply($target, FilterAppliersRegistry $filterAppliers): void
    {
        /** @var int $page */
        $page = $filterAppliers->getFilterValueForKey($this->pageKey(), '1');

        /** @var int $perPage */
        $perPage = $filterAppliers->getFilterValueForKey($this->perPageKey());

        if (-1 !== $perPage) {
            $offset = ($page - 1) * $perPage;
            $target->setFirstResult($offset);

            $limit = $perPage;
            $target->setMaxResults($limit);
        }
    }
}
