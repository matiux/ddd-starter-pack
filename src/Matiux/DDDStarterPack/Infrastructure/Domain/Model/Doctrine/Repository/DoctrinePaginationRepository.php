<?php

namespace DDDStarterPack\Infrastructure\Domain\Model\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;

abstract class DoctrinePaginationRepository extends DoctrineRepository
{
    protected function calculatePagination(QueryBuilder $qb): array
    {
        $totalResult = count($qb->getQuery()->getResult());

        $offset = 0;
        $limit = $totalResult != 0 ? $totalResult : 1;

        return [$offset, $limit];
    }
}
