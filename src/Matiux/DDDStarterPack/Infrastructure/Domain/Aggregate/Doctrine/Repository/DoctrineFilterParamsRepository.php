<?php

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
use DDDStarterPack\Domain\Aggregate\Repository\Paginator\PaginatorWrapper;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class DoctrineFilterParamsRepository extends DoctrineRepository
{
    protected function calculatePagination(FilterParams $filterParams, QueryBuilder $qb): array
    {
        $totalResult = count($qb->getQuery()->getResult());

        $offset = 0;
        $limit = $totalResult != 0 ? $totalResult : 1;

        if (-1 != $filterParams->get('per_page')) {

            $offset = ($filterParams->get('page') - 1) * $filterParams->get('per_page');
            $limit = $filterParams->get('per_page');

        }

        return [$offset, $limit];
    }

    public function byFilterParams(FilterParams $filterParams): PaginatorWrapper
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select($this->getEntityAliasName())
            ->from($this->getEntityClassName(), $this->getEntityAliasName());

        $filterParams->applyTo($qb);

        list($offset, $limit) = $this->calculatePagination($filterParams, $qb);

        $paginator = new DoctrinePaginatorWrapper(new Paginator($qb), $offset, $limit);

        return $paginator;
    }
}
