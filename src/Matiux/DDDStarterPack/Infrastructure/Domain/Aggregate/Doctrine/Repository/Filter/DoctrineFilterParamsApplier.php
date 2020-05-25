<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository\Filter;

use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParams;
use DDDStarterPack\Domain\Aggregate\Repository\Filter\FilterParamsApplier;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DoctrineFilterParamsApplier.
 *
 * @implements FilterParamsApplier<QueryBuilder>
 */
abstract class DoctrineFilterParamsApplier implements FilterParamsApplier
{
    /**
     * @param QueryBuilder $target
     * @param FilterParams $filterParams
     */
    abstract public function apply($target, FilterParams $filterParams): void;
}
