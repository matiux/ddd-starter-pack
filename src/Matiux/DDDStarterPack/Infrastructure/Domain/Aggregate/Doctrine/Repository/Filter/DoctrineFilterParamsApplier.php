<?php

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsApplier;
use Doctrine\ORM\QueryBuilder;

abstract class DoctrineFilterParamsApplier implements FilterParamsApplier
{
    public function apply($target, FilterParams $filterParams): void
    {
        $this->doApply($target, $filterParams);
    }

    abstract protected function doApply(QueryBuilder $qb, FilterParams $filterParams): void;
}
