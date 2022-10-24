<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplier;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterApplierRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @implements FilterApplier<QueryBuilder>
 */
abstract class DoctrineFilterApplier implements FilterApplier
{
    /**
     * @param QueryBuilder          $target
     * @param FilterApplierRegistry $filterApplierRegistry
     */
    abstract public function apply($target, FilterApplierRegistry $filterApplierRegistry): void;
}
