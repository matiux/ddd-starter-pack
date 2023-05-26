<?php

declare(strict_types=1);

namespace DDDStarterPack\Type;

use Iterator;

/**
 * @psalm-suppress UnsafeGenericInstantiation
 *
 * @psalm-immutable
 *
 * @template T of mixed
 *
 * @implements Iterator<array-key, T>
 */
class Collection implements \Iterator, \Countable
{
    /** @var list<T> */
    protected array $items = [];

    /**
     * @param array<array-key, T> $items
     */
    final public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->validateItem($item);
            $this->items[] = $item;
        }
    }

    /**
     * @param T $item
     *
     * @return static<T>
     */
    final public function add($item): static
    {
        $this->validateItem($item);

        return new static([...$this->items, $item]);
    }

    /**
     * @return T
     */
    public function current(): mixed
    {
        return current($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function key(): int|null
    {
        return key($this->items);
    }

    public function valid(): bool
    {
        return null !== key($this->items);
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * @return list<T>
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @template B of Collection<T>
     *
     * @param B $collection
     *
     * @return static
     */
    final public function merge(Collection $collection): static
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
