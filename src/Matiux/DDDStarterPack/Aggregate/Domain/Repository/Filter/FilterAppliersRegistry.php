<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

class FilterAppliersRegistry
{
    /**
     * @param array<array-key, FilterApplier> $filterAppliers
     * @param array                           $neededFilters
     */
    public function __construct(
        private array $filterAppliers,
        protected array $neededFilters
    ) {
    }

    /**
     * @param FilterApplier[] $filterAppliers
     */

    /**
     * @param string $key
     * @param string $default
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function getFilterValueForKey(string $key, string $default = ''): mixed
    {
        if (
            isset($this->neededFilters[$key])
            && !empty($this->neededFilters[$key])
        ) {
            return $this->neededFilters[$key];
        }

        return !empty($default) ? $default : throw new \InvalidArgumentException("Filter with key '{$key}' does not exist");
    }

    public function applyToTarget(mixed $target): void
    {
        foreach ($this->filterAppliers as $applier) {
            if ($applier->supports($this)) {
                $applier->apply($target, $this);
            }
        }
    }

    public function hasFilterWithKey(string $key): bool
    {
        return array_key_exists($key, $this->neededFilters);
    }
}
