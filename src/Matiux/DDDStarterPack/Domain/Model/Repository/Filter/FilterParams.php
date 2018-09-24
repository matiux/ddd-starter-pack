<?php

namespace DDDStarterPack\Domain\Model\Repository\Filter;

class FilterParams
{
    protected $data;

    /** @var array|FilterParamsApplier[] */
    protected $appliers;

    public function __construct(array $appliers, array $data)
    {
        $this->setAppliers($appliers);
        $this->data = $data;
    }

    private function setAppliers(array $appliers)
    {
        foreach ($appliers as $applier) {
            $this->addApplier($applier);
        }
    }

    protected function addApplier(FilterParamsApplier $applier)
    {
        $key = $applier->key();

        if (isset($this->appliers[$key])) {
            throw new \InvalidArgumentException($key . ' is already set');
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

    public function apply($target): void
    {
        foreach ($this->appliers as $applier) {

            $applier->apply($target, $this);
        }
    }
}
