<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;
use DDDStarterPack\Aggregate\Domain\Repository\Paginator\Paginator;
use Doctrine\ORM\QueryBuilder;
use Webmozart\Assert\Assert;

/**
 * @template T
 */
abstract class DoctrineFilterApplierRepository extends DoctrineRepository
{
    /**
     * @param FilterAppliersRegistry $filterAppliers
     *
     * @return Paginator<T>
     */
    protected function doByFilterParamsWithPagination(FilterAppliersRegistry $filterAppliers): Paginator
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

        $results = $qb->getQuery()->getResult();

        Assert::isArray($results);

        return $results;
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
     * @param QueryBuilder   $qb
     *
     * @return list<int>
     */
    protected function calculatePagination(FilterAppliersRegistry $filterAppliers, QueryBuilder $qb): array
    {
        $result = $qb->getQuery()->getResult();

        Assert::true(is_countable($result));

        $totalResult = count($result);

        $offset = 0;
        $limit = 0 != $totalResult ? $totalResult : 1;

        if (-1 != $filterAppliers->getFilterValueForKey('per_page')) {
            $offset = $this->calculateOffset($filterAppliers);

            /** @var int $limit */
            $limit = $filterAppliers->getFilterValueForKey('per_page');
        }

        return [intval($offset), intval($limit)];
    }

    /**
     * @param QueryBuilder $qb
     * @param int          $offset
     * @param int          $limit
     *
     * @return Paginator<T>
     */
    abstract protected function createPaginator(QueryBuilder $qb, int $offset, int $limit): Paginator;

    private function calculateOffset(FilterAppliersRegistry $filterAppliers): int
    {
        /** @var int $page */
        $page = $filterAppliers->getFilterValueForKey('page');

        /** @var int $perPage */
        $perPage = $filterAppliers->getFilterValueForKey('per_page');

        return ($page - 1) * $perPage;
    }
}
