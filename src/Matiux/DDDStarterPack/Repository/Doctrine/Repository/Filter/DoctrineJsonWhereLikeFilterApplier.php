<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;
use Doctrine\ORM\QueryBuilder;

abstract class DoctrineJsonWhereLikeFilterApplier extends DoctrineFilterApplier
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

            match ($this->isQueryCaseSensitive($conf)) {
                true => $this->makeQueryCaseSensitive($key, $conf),
                false => $this->makeQueryCaseInSensitive($key, $conf),
            };
        }
    }

    private function isQueryCaseSensitive(array $conf): bool
    {
        return isset($conf['caseSensitive']) && true === $conf['caseSensitive'];
    }

    /**
     * @param array{path: string, column: string, preProcessor?: callable, caseSensitive?: bool} $conf
     */
    private function makeQueryCaseSensitive(string $key, array $conf): void
    {
        $val = $this->prepareValue($key, $conf);

        $alias = $this->getModelAlias();

        $this->target->andWhere("JSON_SEARCH({$alias}.{$conf['column']}, 'one', :{$key}, NULL, '{$conf['path']}') IS NOT NULL")
            ->setParameter($key, "%{$val}%");
    }

    /**
     * @param array{path: string, column: string, preProcessor?: callable, caseSensitive?: bool} $conf
     */
    private function makeQueryCaseInSensitive(string $key, array $conf): void
    {
        $val = $this->prepareValue($key, $conf);
        $val = is_string($val) ? strtolower($val) : $val;

        $alias = $this->getModelAlias();

        $this->target->andWhere("JSON_SEARCH(LOWER({$alias}.{$conf['column']}), 'one', :{$key}, NULL, LOWER('{$conf['path']}')) IS NOT NULL")
            ->setParameter($key, "%{$val}%");
    }

    /**
     * @param array{path: string, column: string, preProcessor?: callable, caseSensitive?: bool} $conf
     */
    private function prepareValue(string $key, array $conf): float|int|string
    {
        /** @var float|int|string $val */
        $val = $this->appliersRegistry->getFilterValueForKey($key);

        /** @var float|int|string $val */
        $val = (isset($conf['preProcessor'])) ? $conf['preProcessor']($val) : $val;

        self::assertValueTypeIsCorrect($key, $val);

        return $val;
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

    private static function assertValueTypeIsCorrect(string $key, mixed $val): void
    {
        if (is_string($val) || is_float($val) || is_int($val)) {
            return;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'When using the JsonWhereLike applier, the filter value must be an integer, float, or string. [key: %s - val type: %s - val: %s]',
                $key,
                gettype($val),
                is_array($val) ? json_encode($val) : (string) $val,
            ),
        );
    }
}
