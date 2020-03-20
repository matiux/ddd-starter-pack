<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\Filter;

use InvalidArgumentException;

class FilterParams
{
    protected $data;

    /** @var array|FilterParamsApplier[] */
    protected $appliers = [];

    public function __construct(array $appliers, array $data)
    {
        $this->setAppliers($appliers);
        $this->data = $data;
    }

    private function setAppliers(array $appliers): void
    {
        foreach ($appliers as $applier) {
            $this->addApplier($applier);
        }
    }

    protected function addApplier(FilterParamsApplier $applier): void
    {
        $key = $applier->key();

        if (isset($this->appliers[$key])) {
            throw new InvalidArgumentException($key . ' is already set');
        }

        $this->appliers[$key] = $applier;
    }

    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->data)) {

            return $this->data[$key];
        }

        return $default;
    }

    public function applyTo($target): void
    {
        foreach ($this->appliers as $applierKey => $applier) {

            if ($applier->supports($this)) {

                $applier->apply($target, $this);
            }
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }
}
