<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;

abstract class DoctrineWhereEqualsFilterApplier extends DoctrineFilterApplier
{
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        /**
         * @var string                $key
         * @var bool|float|int|string $value //scalar
         */
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

    abstract protected function getSupportedFilters(): array;
}
