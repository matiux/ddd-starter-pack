<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplierRegistry;
use Doctrine\ORM\QueryBuilder;

abstract class DoctrineGenericPaginationApplier extends DoctrineFilterApplier
{
    protected const KEY = 'pagination';

    abstract protected function pageKey(): string;

    abstract protected function perPageKey(): string;

    public function key(): string
    {
        return self::KEY;
    }

    /**
     * @psalm-param QueryBuilder $target
     *
     * @param FilterApplierRegistry $filterApplierRegistry
     * @param mixed                 $target
     */
    public function apply($target, FilterApplierRegistry $filterApplierRegistry): void
    {
        /** @var int $page */
        $page = $filterApplierRegistry->getFilterValueForKey($this->pageKey(), '1');

        /** @var int $perPage */
        $perPage = $filterApplierRegistry->getFilterValueForKey($this->perPageKey());

        if (-1 !== $perPage) {
            $offset = ($page - 1) * $perPage;
            $target->setFirstResult($offset);

            $limit = $perPage;
            $target->setMaxResults($limit);
        }
    }
}
