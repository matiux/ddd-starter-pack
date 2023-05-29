<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;
use DDDStarterPack\Repository\Filter\SortingKeyFilterApplier;
use Doctrine\ORM\QueryBuilder;
use Webmozart\Assert\Assert;

abstract class DoctrineGenericSortApplier extends DoctrineFilterApplier
{
    protected function sortDirectionKey(): string
    {
        return SortingKeyFilterApplier::SORT_DIRECTION;
    }

    protected function sortKey(): string
    {
        return SortingKeyFilterApplier::SORT;
    }

    /**
     * @param QueryBuilder $target
     */
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        try {
            $sortField = (string) $appliersRegistry->getFilterValueForKey($this->sortKey());
            $sortDirection = (string) $appliersRegistry->getFilterValueForKey($this->sortDirectionKey(), 'ASC');

            Assert::keyExists($this->getFieldsMap(), $sortField, sprintf('Invalid sort key: %s', $sortField));

            $target->orderBy($this->getFieldsMap()[$sortField], $sortDirection);
        } catch (\InvalidArgumentException $e) {
            return;
        }
    }

    public function supports(FilterAppliersRegistry $appliersRegistry): bool
    {
        return
            $appliersRegistry->hasFilterWithKey(SortingKeyFilterApplier::SORT)
            || $appliersRegistry->hasFilterWithKey(SortingKeyFilterApplier::SORT_DIRECTION);
    }

    /**
     * @return string[]
     */
    abstract protected function getFieldsMap(): array;
}
