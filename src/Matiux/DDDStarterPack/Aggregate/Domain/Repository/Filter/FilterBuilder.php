<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

class FilterBuilder
{
    /** @var FilterApplier[] */
    protected array $filterAppliers = [];

    protected bool $frozen = false;

    /**
     * @param FilterApplier[]|\Traversable<FilterApplier> $filterAppliers
     */
    public function __construct($filterAppliers = [])
    {
        if ($filterAppliers instanceof \Traversable) {
            $filterAppliers = iterator_to_array($filterAppliers);
        }

        foreach ($filterAppliers as $applier) {
            $this->addApplier($applier);
        }
    }

    public function addApplier(FilterApplier $filterApplier): void
    {
        if ($this->frozen) {
            throw new \LogicException('The builder is frozen');
        }

        $this->filterAppliers[] = $filterApplier;
    }

    public function build(array $neededFilters): FilterAppliersRegistry
    {
        $this->frozen = true;

        return new FilterAppliersRegistry(array_values($this->filterAppliers), $neededFilters);
    }
}
