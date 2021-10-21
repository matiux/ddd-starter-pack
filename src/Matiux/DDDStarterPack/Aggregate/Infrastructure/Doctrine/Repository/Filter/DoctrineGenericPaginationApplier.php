<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterParams;
use Doctrine\ORM\QueryBuilder;

abstract class DoctrineGenericPaginationApplier extends DoctrineFilterParamsApplier
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
     * @param FilterParams $filterParams
     * @param mixed        $target
     */
    public function apply($target, FilterParams $filterParams): void
    {
        /** @var int $page */
        $page = $filterParams->getFilterValueForKey($this->pageKey(), '1');

        /** @var int $perPage */
        $perPage = $filterParams->getFilterValueForKey($this->perPageKey());

        if (-1 !== $perPage) {
            $offset = ($page - 1) * $perPage;
            $target->setFirstResult($offset);

            $limit = $perPage;
            $target->setMaxResults($limit);
        }
    }
}
