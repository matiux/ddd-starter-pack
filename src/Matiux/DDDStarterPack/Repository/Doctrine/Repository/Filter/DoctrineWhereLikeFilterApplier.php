<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;

abstract class DoctrineWhereLikeFilterApplier extends DoctrineFilterApplier
{
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        foreach ($this->getSupportedFilters() as $key) {
            if ($appliersRegistry->hasFilterWithKey($key)) {
                /** @var float|int|string $val */
                $val = $appliersRegistry->getFilterValueForKey($key);

                self::assertValueTypeIsCorrect($key, $val);

                $target->andWhere(
                    sprintf('%s.%s LIKE :%s', $this->getModelAlias(), $key, $key),
                )->setParameter($key, sprintf('%%%s%%', $val));
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

    private static function assertValueTypeIsCorrect(string $key, mixed $val): void
    {
        if (is_string($val) || is_float($val) || is_int($val)) {
            return;
        }

        throw new \InvalidArgumentException(
            sprintf(
                'When using the WhereLike applier, the filter value must be an integer, float, or string. [key: %s - val type: %s - val: %s]',
                $key,
                gettype($val),
                is_array($val) ? json_encode($val) : (string) $val,
            ),
        );
    }
}
