<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;

abstract class DoctrineWhereEqualsFilterApplier extends DoctrineFilterApplier
{
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        foreach ($this->getSupportedFilters() as $key) {
            if ($appliersRegistry->hasFilterWithKey($key)) {
                $target->andWhere(
                    sprintf('%s.%s = :%s', $this->getModelAlias(), $key, $key),
                )->setParameter($key, $appliersRegistry->getFilterValueForKey($key));
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
