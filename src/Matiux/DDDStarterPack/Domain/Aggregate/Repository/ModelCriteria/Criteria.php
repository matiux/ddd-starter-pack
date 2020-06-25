<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate\Repository\ModelCriteria;

use Countable;
use Iterator;

abstract class Criteria implements Iterator, Countable
{
    /** @var Criterion[] */
    protected $criteria = [];

    public function add(Criterion $criterion): Criteria
    {
        array_push($this->criteria, $criterion);

        return $this;
    }

    public function current(): Criterion
    {
        return current($this->criteria);
    }

    public function next(): void
    {
        next($this->criteria);
    }

    /** @return null|int|string */
    public function key()
    {
        return key($this->criteria);
    }

    public function valid()
    {
        $key = key($this->criteria);

        return null !== $key;
    }

    public function rewind()
    {
        reset($this->criteria);
    }

    public function count(): int
    {
        return count($this->criteria);
    }
}
