<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Infrastructure\Doctrine\Repository\Filter;

use DDDStarterPack\Aggregate\Domain\Repository\Filter\FilterAppliersRegistry;
use DDDStarterPack\Aggregate\Domain\Repository\Filter\SortingKeyFilterApplier;
use Doctrine\ORM\QueryBuilder;
use Webmozart\Assert\Assert;

class DoctrineGenericSortApplier extends DoctrineFilterApplier
{
    /**
     * @param string[] $fieldsMap
     */
    public function __construct(protected array $fieldsMap)
    {
    }

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

            Assert::keyExists($this->fieldsMap, $sortField, sprintf('Invalid sort key: %s', $sortField));

            $target->orderBy($this->fieldsMap[$sortField], $sortDirection);
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
}
