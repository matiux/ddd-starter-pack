<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;

abstract class DoctrineWhereInFilterApplier extends DoctrineFilterApplier
{
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        foreach ($this->getSupportedFilters() as $key) {
            if ($appliersRegistry->hasFilterWithKey($key)) {
                $target->andWhere(
                    sprintf('%s.%s IN (:%s)', $this->getModelAlias(), $key, $key),
                )->setParameter(
                    $key,
                    (array) $appliersRegistry->getFilterValueForKey($key),
                );
            }
        }
    }

    public function supports(FilterAppliersRegistry $appliersRegistry): bool
    {
        $diff = array_intersect_key(array_flip($this->getSupportedFilters()), $appliersRegistry->requestedFilters());

        return !empty($diff);
    }

    abstract protected function getModelAlias(): string;

    /**
     * @return string[]
     */
    abstract protected function getSupportedFilters(): array;
}
