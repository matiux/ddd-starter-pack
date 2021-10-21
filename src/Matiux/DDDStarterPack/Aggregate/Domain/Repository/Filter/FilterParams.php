<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

use InvalidArgumentException;

class FilterParams
{
    /** @var FilterParamsApplier[] */
    protected array $appliers = [];

    /**
     * @param array<array-key, FilterParamsApplier> $appliers
     * @param array                                 $neededFilters
     */
    public function __construct(array $appliers, protected array $neededFilters)
    {
        $this->setAppliers($appliers);
    }

    /**
     * @param FilterParamsApplier[] $appliers
     */
    private function setAppliers(array $appliers): void
    {
        foreach ($appliers as $applier) {
            $this->addApplier($applier);
        }
    }

    public function addApplier(FilterParamsApplier $applier): void
    {
        $key = $applier->key();

        if (isset($this->appliers[$key])) {
            throw new InvalidArgumentException("Applier for key '{$key}' is already set");
        }

        $this->appliers[$key] = $applier;
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
        foreach ($this->appliers as $applier) {
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
