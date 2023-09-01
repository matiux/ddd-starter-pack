<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;

abstract class DoctrineJsonWhereEqualsFilterApplier extends DoctrineFilterApplier
{
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        foreach ($this->getSupportedFilters() as $key => $conf) {
            if ($appliersRegistry->hasFilterWithKey($key)) {
                /** @var float|int|string $val */
                $val = $appliersRegistry->getFilterValueForKey($key);

                self::assertValueTypeIsCorrect($key, $val);

                $alias = $this->getModelAlias();

                $target->andWhere("JSON_SEARCH({$alias}.{$conf['column']}, 'one', :{$key}, NULL, '{$conf['path']}') IS NOT NULL")
                    ->setParameter($key, $val);
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
     * @return array<string, array{path: string, column: string}>
     */
    abstract protected function getSupportedFilters(): array;

    private static function assertValueTypeIsCorrect(string $key, mixed $val): void
    {
        if (is_string($val) || is_float($val) || is_int($val)) {
            return;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'When using the JsonWhereEquals applier, the filter value must be an integer, float, or string. [key: %s - val type: %s - val: %s]',
                $key,
                gettype($val),
                is_array($val) ? json_encode($val) : (string) $val,
            ),
        );
    }
}
