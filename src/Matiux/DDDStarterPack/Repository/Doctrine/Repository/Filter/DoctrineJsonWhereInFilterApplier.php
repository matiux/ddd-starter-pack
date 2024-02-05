<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;

abstract class DoctrineJsonWhereInFilterApplier extends DoctrineFilterApplier
{
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        foreach ($this->getSupportedFilters() as $key => $conf) {
            if ($appliersRegistry->hasFilterWithKey($key)) {
                /** @var float[]|int[]|string[] $vals */
                $vals = $appliersRegistry->getFilterValueForKey($key);

                $alias = $this->getModelAlias();

                $condizioni = [];

                foreach ($vals as $counter => $val) {
                    $paramName = ":val{$counter}";

                    $condizioni[] = $target->expr()->isNotNull("JSON_SEARCH({$alias}.{$conf['column']}, 'one', {$paramName}, NULL, '{$conf['path']}')");
                }

                $target->andWhere(
                    $target->expr()->andX($target->expr()->orX(...$condizioni)),
                );

                foreach ($vals as $counter => $val) {
                    /** @var float|int|string $val */
                    $val = (isset($conf['preProcessor'])) ? $conf['preProcessor']($val) : $val;

                    $target->setParameter(":val{$counter}", $val);
                }
            }
        }
    }

    public function supports(FilterAppliersRegistry $appliersRegistry): bool
    {
        $supportedFilters = array_flip(array_keys($this->getSupportedFilters()));
        $requestedFilters = $appliersRegistry->requestedFilters();

        $diff = array_intersect_key($supportedFilters, $requestedFilters);

        return !empty($diff);
    }

    abstract protected function getModelAlias(): string;

    /**
     * @return array<string, array{path: string, column: string, preProcessor?: callable}>
     */
    abstract protected function getSupportedFilters(): array;
}
