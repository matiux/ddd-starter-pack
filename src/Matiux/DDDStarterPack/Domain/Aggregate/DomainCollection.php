<?php

namespace DDDStarterPack\Domain\Aggregate;

use ArrayObject;
use Countable;
use Iterator;

class DomainCollection implements Iterator, Countable
{
    private $position = 0;

    private $items = [];

    public function __construct(ArrayObject $items = null)
    {
        if (null === $items) {
            return;
        }

        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add($item): void
    {
        $this->validateItem($item);

        $this->items[] = $item;
    }

    protected function validateItem($item): void
    {
        return;
    }

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
}
