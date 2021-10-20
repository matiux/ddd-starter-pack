<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain;

use Countable;
use Iterator;

/**
 * @psalm-suppress UnsafeGenericInstantiation
 * @template T
 * @implements Iterator<array-key, T>
 */
class DomainCollection implements Iterator, Countable
{
    private int $position = 0;

    /** @var array<int, T> */
    private array $items = [];

    /**
     * @param array<array-key, T> $items
     */
    final public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * @param T $item
     */
    public function add($item): void
    {
        $this->validateItem($item);

        $this->items[] = $item;
    }

    /**
     * @return T
     */
    public function current()
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return T[]
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @template B of DomainCollection<T>
     *
     * @param B $collection
     *
     * @return static
     */
    final public function merge($collection): static
    {
        $items = array_merge($this->items, $collection->items);

        return new static($items);
    }

    /**
     * @param T $item
     */
    protected function validateItem($item): void
    {
    }
}
