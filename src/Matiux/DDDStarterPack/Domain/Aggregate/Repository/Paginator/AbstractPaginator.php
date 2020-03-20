<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\Paginator;

use ArrayIterator;
use IteratorAggregate;
use Traversable;
use UnexpectedValueException;

abstract class AbstractPaginator implements Paginator
{
    protected $iteratorAggregate;
    protected $iterator;
    protected $offset;
    protected $limit;

    public function __construct(IteratorAggregate $iteratorAggregate, int $offset, int $limit)
    {
        $this->iteratorAggregate = $iteratorAggregate;
        $this->iterator = $iteratorAggregate->getIterator();
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function getPerPageNumber(): int
    {
        return $this->limit;
    }

    public function getCurrentPage(): int
    {
        return 0 === $this->limit ? 1 : ($this->offset / $this->limit) + 1;
    }

    public function count(): int
    {
        return count($this->getIterator());
    }

    public function getIterator(): Traversable
    {
        return $this->iterator;
    }

    public function getCurrentPageCollection(): array
    {
        if (!($this->getIterator() instanceof ArrayIterator)) {

            throw new UnexpectedValueException('Iterator must be an instance of \ArrayIterator');
        }

        return $this->getIterator()->getArrayCopy();
    }

    public function getTotalPage(): int
    {
        $tot = $this->getTotalResult();

        return (int)ceil($tot / $this->limit);
    }

    public function getTotalResult(): int
    {
        return $this->iteratorAggregate->count();
    }
}
