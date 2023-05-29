<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterApplier;
use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @implements FilterApplier<QueryBuilder>
 */
abstract class DoctrineFilterApplier implements FilterApplier
{
    /**
     * @param QueryBuilder $target
     */
    abstract public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void;
}
