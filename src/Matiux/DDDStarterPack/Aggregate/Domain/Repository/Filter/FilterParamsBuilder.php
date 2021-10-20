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

    public function addApplier(FilterParamsApplier $applier): void
    {
        if ($this->frozen) {
            throw new LogicException('The builder is frozen');
        }

        $key = $applier->key();

        if (isset($this->appliers[$key])) {
            throw new InvalidArgumentException('Applier "'.$key.'" is already set');
        }

        $this->appliers[$key] = $applier;
    }

    public function build(array $data): FilterParams
    {
        $this->frozen = true;

        //$options = array_merge($this->getDefaultOptions(), $options);

        //$this->applyOptions($options, $filterParams);

        return new FilterParams(array_values($this->appliers), $data);
    }
}
