<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\Filter;

use LogicException;

class FilterParamsBuilder
{
    protected $appliers = [];

    protected $frozen = false;

    public function addApplier(FilterParamsApplier $applier): void
    {
        if ($this->frozen) {
            throw new LogicException('The builder is frozen');
        }

        $key = $applier->key();

        if (isset($this->appliers[$key])) {
            throw new \InvalidArgumentException('Applier "' . $key . '" is already set');
        }

        $this->appliers[$key] = $applier;
    }

    public function build(array $data, $options = [])
    {
        $this->frozen = true;

        //$options = array_merge($this->getDefaultOptions(), $options);

        //$this->applyOptions($options, $filterParams);

        return new FilterParams(array_values($this->appliers), $data);
    }
}
