<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

use InvalidArgumentException;

class FilterApplierRegistry
{
    /** @var FilterApplier[] */
    protected array $filterAppliers = [];

    /**
     * @param array<array-key, FilterApplier> $filterAppliers
     * @param array                           $neededFilters
     */
    public function __construct(array $filterAppliers, protected array $neededFilters)
    {
        $this->setAppliers($filterAppliers);
    }

    /**
     * @param FilterApplier[] $filterAppliers
     */
    private function setAppliers(array $filterAppliers): void
    {
        foreach ($filterAppliers as $applier) {
            $this->addApplier($applier);
        }
    }

    public function addApplier(FilterApplier $filterApplier): void
    {
        $key = $filterApplier->key();

        if (isset($this->filterAppliers[$key])) {
            throw new InvalidArgumentException("Applier for key '{$key}' is already set");
        }

        $this->filterAppliers[$key] = $filterApplier;
    }

    /**
     * @param string $key
     * @param string $default
     *
     * @throws InvalidArgumentException
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

        return !empty($default) ? $default : throw new InvalidArgumentException("Filter with key '{$key}' does not exist");
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
