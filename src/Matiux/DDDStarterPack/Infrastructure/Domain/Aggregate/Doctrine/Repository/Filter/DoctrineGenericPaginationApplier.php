<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
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
     * @param QueryBuilder $target
     * @param FilterParams $filterParams
     */
    public function apply($target, FilterParams $filterParams): void
    {
        /** @var int $page */
        $page = $filterParams->get($this->pageKey());

        /** @var int $perPage */
        $perPage = $filterParams->get($this->perPageKey());

        if (-1 !== $perPage) {
            $offset = ($page - 1) * $perPage;
            $target->setFirstResult($offset);

            $limit = $perPage;
            $target->setMaxResults($limit);
        }
    }
}
