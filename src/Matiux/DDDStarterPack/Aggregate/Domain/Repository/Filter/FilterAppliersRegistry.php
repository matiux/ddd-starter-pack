<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

class FilterAppliersRegistry
{
    /**
     * @param array<array-key, FilterApplier> $filterAppliers
     * @param array<string, mixed>            $requestedFilters
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
            isset($this->requestedFilters[$key])
            && !empty($this->requestedFilters[$key])
        ) {
            return $this->requestedFilters[$key];
        }

        return !empty($default) ? $default : throw new \InvalidArgumentException("Filter with key '{$key}' does not exist");
    }

    public function applyToTarget(mixed $target): void
    {
        foreach ($this->filterAppliers as $applier) {
            if ($applier->supports($this)) {
                $applier->applyTo($target, $this);
            }
        }

//        /** @var mixed $requestedFilterValue */
//        foreach ($this->requestedFilters as $requestedFilterKey => $requestedFilterValue) {
//            foreach ($this->filterAppliers as $applier) {
//                if ($applier->supports($this, $requestedFilterKey)) {
//                    $applier->applyTo($target, $this, $requestedFilterKey);
//
//                    break; // Only one applyer can support a key
//                }
//            }
//        }
    }

    public function hasFilterWithKey(string $key): bool
    {
        return array_key_exists($key, $this->requestedFilters);
    }
}
