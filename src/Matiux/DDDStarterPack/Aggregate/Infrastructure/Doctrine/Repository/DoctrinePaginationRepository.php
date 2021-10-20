<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\QueryBuilder;
use Webmozart\Assert\Assert;

abstract class DoctrinePaginationRepository extends DoctrineRepository
{
    protected function calculatePagination(QueryBuilder $qb): array
    {
        $result = $qb->getQuery()->getResult();

        Assert::true(is_countable($result));

        $totalResult = count($result);

        $offset = 0;
        $limit = 0 != $totalResult ? $totalResult : 1;

        return [$offset, $limit];
    }
}
