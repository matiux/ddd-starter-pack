<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Filter;

class FilterAppliersRegistry
{
    /**
     * @param array<array-key, FilterApplier> $filterAppliers
     * @param array                           $requestedFilters
     */
    public function __construct(
        private array $filterAppliers,
        private array $requestedFilters,
    ) {
    }

    /**
     * @param FilterApplier[] $filterAppliers
     */

    /**
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function getFilterValueForKey(string $key, string $default = null): mixed
    {
        if (array_key_exists($key, $this->requestedFilters)) {
            return $this->requestedFilters[$key];
        }

        return $default ?? throw new \InvalidArgumentException("Filter with key '{$key}' does not exist");
    }

    public function applyToTarget(mixed $target): void
    {
        foreach ($this->filterAppliers as $applier) {
            if ($applier->supports($this)) {
                $applier->applyTo($target, $this);
            }
        }
    }

    public function hasFilterWithKey(string $key): bool
    {
        return array_key_exists($key, $this->requestedFilters);
    }

    public function requestedFilters(): array
    {
        return $this->requestedFilters;
    }
}
