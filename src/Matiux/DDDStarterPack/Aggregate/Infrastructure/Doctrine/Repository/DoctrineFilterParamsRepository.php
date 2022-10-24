<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplierRegistry;
use DDDStarterPack\Aggregate\Domain\Repository\Paginator\Paginator;
use Doctrine\ORM\QueryBuilder;
use Webmozart\Assert\Assert;

/**
 * @template T
 */
abstract class DoctrineFilterParamsRepository extends DoctrineRepository
{
    /**
     * @param FilterApplierRegistry $filterApplierRegistry
     *
     * @return Paginator<T>
     */
    protected function doByFilterParamsWithPagination(FilterApplierRegistry $filterApplierRegistry): Paginator
    {
        $qb = $this->createQuery($filterApplierRegistry);

        [$offset, $limit] = $this->calculatePagination($filterApplierRegistry, $qb);

        return $this->createPaginator($qb, $offset, $limit);
    }

    /**
     * @param FilterApplierRegistry $filterApplierRegistry
     *
     * @return T[]
     */
    protected function doByFilterParams(FilterApplierRegistry $filterApplierRegistry): array
    {
        $qb = $this->createQuery($filterApplierRegistry);

        $results = $qb->getQuery()->getResult();

        Assert::isArray($results);

        return $results;
    }

    private function createQuery(FilterApplierRegistry $filterApplierRegistry): QueryBuilder
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select($this->getEntityAliasName())
            ->from($this->getEntityClassName(), $this->getEntityAliasName());

        $filterApplierRegistry->applyToTarget($qb);

        return $qb;
    }

    /**
     * @param FilterApplierRegistry $filterApplierRegistry
     * @param QueryBuilder          $qb
     *
     * @return list<int>
     */
    protected function calculatePagination(FilterApplierRegistry $filterApplierRegistry, QueryBuilder $qb): array
    {
        $result = $qb->getQuery()->getResult();

        Assert::true(is_countable($result));

        $totalResult = count($result);

        $offset = 0;
        $limit = 0 != $totalResult ? $totalResult : 1;

        if (-1 != $filterApplierRegistry->getFilterValueForKey('per_page')) {
            $offset = $this->calculateOffset($filterApplierRegistry);

            /** @var int $limit */
            $limit = $filterApplierRegistry->getFilterValueForKey('per_page');
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

    private function calculateOffset(FilterApplierRegistry $filterApplierRegistry): int
    {
        /** @var int $page */
        $page = $filterApplierRegistry->getFilterValueForKey('page');

        /** @var int $perPage */
        $perPage = $filterApplierRegistry->getFilterValueForKey('per_page');

        return ($page - 1) * $perPage;
    }
}
