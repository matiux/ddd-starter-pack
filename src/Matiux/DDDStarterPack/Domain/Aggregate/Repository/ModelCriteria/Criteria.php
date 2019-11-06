<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\ModelCriteria;

abstract class Criteria implements \Iterator, \Countable
{
    /**
     * @var Criterion[]
     */
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

    public function next()
    {
        return next($this->criteria);
    }

    public function key(): string
    {
        return key($this->criteria);
    }

    public function valid()
    {
        $key = key($this->criteria);

        return ($key !== NULL && $key !== FALSE);
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
