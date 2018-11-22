<?php

namespace Tests\DDDStarterPack\Support\Domain\Model\Repository\Filter;

use DDDStarterPack\Domain\Model\Repository\Filter\FilterParams;
use DDDStarterPack\Domain\Model\Repository\Filter\FilterParamsApplier;

class DummyFilterParamsApplier implements FilterParamsApplier
{
    private $key;

    public function __construct(string $key)
    {
        if (!$key) {
            throw new \InvalidArgumentException('the given key is empty');
        }

        $this->key = $key;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function apply($target, FilterParams $filterParams): void
    {
        $this->doApply($target, $filterParams);
    }

    protected function doApply(DummyArrayTarget $target, FilterParams $filterParams)
    {
        $target->add([
            $this->key() => $filterParams->get($this->key())
        ]);
    }

    public function supports(FilterParams $filterParams): bool
    {
        return true;
    }
}
