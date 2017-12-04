<?php

namespace DDDStarterPack\Domain\Model\Repository;

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
        $criterion = current($this->criteria);

        return $criterion;
    }

    public function next()
    {
        $criterion = next($this->criteria);

        return $criterion;
    }

    public function key(): string
    {
        $key = key($this->criteria);

        return $key;
    }

    public function valid()
    {
        $key = key($this->criteria);

        $var = ($key !== NULL && $key !== FALSE);

        return $var;
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
