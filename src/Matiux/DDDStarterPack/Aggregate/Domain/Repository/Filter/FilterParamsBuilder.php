<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Filter;

use InvalidArgumentException;
use LogicException;

class FilterParamsBuilder
{
    /** @var FilterParamsApplier[] */
    protected array $appliers = [];

    protected bool $frozen = false;

    /**
     * @param FilterParamsApplier[] $appliers
     */
    public function __construct(array $appliers = [])
    {
        foreach ($appliers as $applier) {
            $this->addApplier($applier);
        }
    }

    public function addApplier(FilterParamsApplier $applier): void
    {
        if ($this->frozen) {
            throw new LogicException('The builder is frozen');
        }

        $key = $applier->key();

        if (isset($this->appliers[$key])) {
            throw new InvalidArgumentException("Applier for key '{$key}' is already set");
        }

        $this->appliers[$key] = $applier;
    }

    public function build(array $neededFilters): FilterParams
    {
        $this->frozen = true;

        return new FilterParams(array_values($this->appliers), $neededFilters);
    }
}
