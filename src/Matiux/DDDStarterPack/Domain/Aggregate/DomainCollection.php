<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate;

use Countable;
use Iterator;

/**
 * @template T
 */
class DomainCollection implements Iterator, Countable
{
    /** @var int */
    private $position = 0;

    /** @var array<int, T> */
    private $items = [];

    /**
     * @param array<int, T> $items
     */
    final public function __construct(array $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    /**
     * @psalm-param T $item
     *
     * @param mixed $item
     */
    public function add($item): void
    {
        $this->validateItem($item);

        $this->items[] = $item;
    }

    /**
     * @param T $item
     */
    protected function validateItem($item): void
    {
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

    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @param self $collection
     *
     * @return static
     */
    public function merge($collection)
    {
        $items = array_merge($this->items, $collection->items);

        return new static($items);
    }
}
