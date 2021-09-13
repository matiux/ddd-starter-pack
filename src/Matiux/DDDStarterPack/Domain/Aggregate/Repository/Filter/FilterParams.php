<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate\Repository\Filter;

use InvalidArgumentException;

class FilterParams
{
    /** @var FilterParamsApplier[] */
    protected array $appliers = [];

    /**
     * @param array<array-key, FilterParamsApplier> $appliers
     * @param array                                 $data
     */
    public function __construct(array $appliers, protected array $data)
    {
        $this->setAppliers($appliers);
    }

    /**
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        return $default;
    }

    public function applyTo(mixed $target): void
    {
        foreach ($this->appliers as $applier) {
            if ($applier->supports($this)) {
                $applier->apply($target, $this);
            }
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    protected function addApplier(FilterParamsApplier $applier): void
    {
        $key = $applier->key();

        if (isset($this->appliers[$key])) {
            throw new InvalidArgumentException($key.' is already set');
        }

        $this->appliers[$key] = $applier;
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
}
