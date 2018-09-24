<?php

namespace DDDStarterPack\Domain\Model\Repository\Filter;

class FilterParamsBuilder
{
    protected $appliers = [];

    protected $frozen = false;

    public function addApplier(FilterParamsApplier $applier): void
    {
        if ($this->frozen) {
            throw new \LogicException('The builder is frozen');
        }

        $key = $applier->key();

        if (isset($this->appliers[$key])) {
            throw new \InvalidArgumentException($key . ' is set yet');
        }

        $this->appliers[$key] = $applier;
    }

    public function build(array $data, $options = [])
    {
        $this->frozen = true;

        //$options = array_merge($this->getDefaultOptions(), $options);

        $filterParams = new FilterParams(array_values($this->appliers), $data);

        //$this->applyOptions($options, $filterParams);

        return $filterParams;
    }
}
