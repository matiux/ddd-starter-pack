<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;
use Doctrine\ORM\QueryBuilder;

abstract class DoctrineJsonWhereInFilterApplier extends DoctrineFilterApplier
{
    private QueryBuilder $target;
    private FilterAppliersRegistry $appliersRegistry;

    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        $this->target = $target;
        $this->appliersRegistry = $appliersRegistry;

        foreach ($this->getSupportedFilters() as $key => $conf) {
            if (!$this->appliersRegistry->hasFilterWithKey($key)) {
                continue;
            }

            /** @var float[]|int[]|string[] $vals */
            $vals = $this->appliersRegistry->getFilterValueForKey($key);

            $this->setQueryConditions($vals, $key, $conf);
            $this->setQueryParameters($vals, $key, $conf);
        }
    }

    /**
     * @param float[]|int[]|string[] $vals
     */
    private function setQueryConditions(array $vals, string $key, array $conf): void
    {
        $alias = $this->getModelAlias();

        $condizioni = [];

        foreach ($vals as $counter => $val) {
            $paramName = ":val{$key}{$counter}";

            if ($this->isQueryCaseSensitive($conf)) {
                $condizioni[] = $this->target->expr()->isNotNull("JSON_SEARCH({$alias}.{$conf['column']}, 'one', {$paramName}, NULL, '{$conf['path']}')");

                continue;
            }

            $condizioni[] = $this->target->expr()->isNotNull("JSON_SEARCH(LOWER({$alias}.{$conf['column']}), 'one', {$paramName}, NULL, LOWER('{$conf['path']}'))");
        }

        $this->target->andWhere(
            $this->target->expr()->andX($this->target->expr()->orX(...$condizioni)),
        );
    }

    private function isQueryCaseSensitive(array $conf): bool
    {
        return isset($conf['caseSensitive']) && true === $conf['caseSensitive'];
    }

    /**
     * @param float[]|int[]|string[]                                                             $vals
     * @param array{path: string, column: string, preProcessor?: callable, caseSensitive?: bool} $conf
     */
    private function setQueryParameters(array $vals, string $key, array $conf): void
    {
        foreach ($vals as $counter => $val) {
            /** @var float|int|string $val */
            $val = (isset($conf['preProcessor'])) ? $conf['preProcessor']($val) : $val;

            if (!$this->isQueryCaseSensitive($conf)) {
                $val = is_string($val) ? strtolower($val) : $val;
            }

            $this->target->setParameter(":val{$key}{$counter}", $val);
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
     * @return array<string, array{path: string, column: string, preProcessor?: callable, caseSensitive?: bool}>
     */
    abstract protected function getSupportedFilters(): array;
}
