<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Doctrine\Repository\Filter;

use DDDStarterPack\Repository\Filter\FilterAppliersRegistry;

abstract class DoctrineWhereEqualsFilterApplier extends DoctrineFilterApplier
{
    public function applyTo($target, FilterAppliersRegistry $appliersRegistry): void
    {
        foreach ($this->getSupportedFilters() as $key => $conf) {
            if (is_string($conf)) {
                $key = $conf;
                $conf = [];
            }

            /** @var string $key */
            if ($appliersRegistry->hasFilterWithKey($key)) {
                /** @var mixed $val */
                $val = $appliersRegistry->getFilterValueForKey($key);

                /** @var mixed $val */
                $val = (isset($conf['preProcessor'])) ? $conf['preProcessor']($val) : $val;

                $target->andWhere(
                    sprintf('%s.%s = :%s', $this->getModelAlias(), $key, $key),
                )->setParameter($key, $val);
            }
        }
    }

    public function supports(FilterAppliersRegistry $appliersRegistry): bool
    {
        $supportedFilters = [];
        foreach ($this->getSupportedFilters() as $key => $conf) {
            $supportedFilters[] = (is_string($conf)) ? $conf : $key;
        }

        $diff = array_intersect_key(array_flip($supportedFilters), $appliersRegistry->requestedFilters());

        return !empty($diff);
    }

    abstract protected function getModelAlias(): string;

    /**
     * @return array<int, string>|array<string, array{preProcessor?: callable}>
     */
    abstract protected function getSupportedFilters(): array;
}
