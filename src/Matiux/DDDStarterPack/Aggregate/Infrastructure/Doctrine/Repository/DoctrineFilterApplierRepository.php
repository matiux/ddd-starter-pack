<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;
use DDDStarterPack\Aggregate\Domain\Repository\Paginator\Paginator;
use DDDStarterPack\Aggregate\Domain\Repository\Paginator\PaginatorI;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

/**
 * @template T
 */
abstract class DoctrineFilterApplierRepository extends DoctrineRepository
{
    /**
     * @param FilterAppliersRegistry $filterAppliers
     *
     * @return PaginatorI<T>
     */
    protected function doByFilterParamsWithPagination(FilterAppliersRegistry $filterAppliers): PaginatorI
    {
        $qb = $this->createQuery($filterAppliers);

        [$offset, $limit] = $this->calculatePagination($filterAppliers, $qb);

        return $this->createPaginator($qb, $offset, $limit);
    }

    /**
     * @param FilterAppliersRegistry $filterAppliers
     *
     * @return T[]
     */
    protected function doByFilterParams(FilterAppliersRegistry $filterAppliers): array
    {
        $qb = $this->createQuery($filterAppliers);

        return $qb->getQuery()->getResult();
    }

    private function createQuery(FilterAppliersRegistry $filterAppliers): QueryBuilder
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select($this->getEntityAliasName())
            ->from($this->getEntityClassName(), $this->getEntityAliasName());

        $filterAppliers->applyToTarget($qb);

        return $qb;
    }

    /**
     * @param FilterAppliersRegistry $filterAppliers
     * @param QueryBuilder           $qb
     *
     * @return array{0: int, 1: int}
     */
    protected function calculatePagination(FilterAppliersRegistry $filterAppliers, QueryBuilder $qb): array
    {
        $result = $qb->getQuery()->getResult();

        $totalResult = count($result);

        $offset = 0;
        $limit = 0 != $totalResult ? $totalResult : 1;

        if (
            $filterAppliers->hasFilterWithKey('per_page')
            && -1 != $filterAppliers->getFilterValueForKey('per_page')
        ) {
            $offset = $this->calculateOffset($filterAppliers);

            $limit = intval($filterAppliers->getFilterValueForKey('per_page'));
        }

        return [$offset, $limit];
    }

    /**
     * @param QueryBuilder $qb
     * @param int          $offset
     * @param int          $limit
     *
     * @return PaginatorI<T>
     */
    protected function createPaginator(QueryBuilder $qb, int $offset, int $limit): PaginatorI
    {
        /** @var array<int, T> $res */
        $res = $qb->getQuery()->getResult();
        $doctrinePaginator = new DoctrinePaginator($qb);

        return new Paginator($res, $offset, $limit, $doctrinePaginator->count());
    }

    private function calculateOffset(FilterAppliersRegistry $filterAppliers): int
    {
        /** @var int $page */
        $page = $filterAppliers->getFilterValueForKey('page');

        /** @var int $perPage */
        $perPage = $filterAppliers->getFilterValueForKey('per_page');

        return ($page - 1) * $perPage;
    }
}
