<?php

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
use DDDStarterPack\Domain\Aggregate\Repository\Paginator\Paginator;
use Doctrine\ORM\QueryBuilder;

abstract class DoctrineFilterParamsRepository extends DoctrineRepository
{
    protected function doByFilterParams(FilterParams $filterParams): Paginator
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select($this->getEntityAliasName())
            ->from($this->getEntityClassName(), $this->getEntityAliasName());

        $filterParams->applyTo($qb);

        list($offset, $limit) = $this->calculatePagination($filterParams, $qb);

        return $this->createPaginator($qb, $offset, $limit);
    }

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

    /**
     * @param QueryBuilder $qb
     * @param int $offset
     * @param int $limit
     * @return Paginator
     */
    abstract protected function createPaginator(QueryBuilder $qb, int $offset, int $limit);
}
