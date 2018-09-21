<?php

namespace DDDStarterPack\Domain\Model\Repository;

abstract class PaginatorWrapper implements Paginator, \Countable
{
    protected $aggregate;
    protected $offset;
    protected $limit;
    protected $iterator;

    public function __construct(\IteratorAggregate $aggregate, $offset, $limit)
    {
        $this->aggregate = $aggregate;

        $this->iterator = $this->aggregate->getIterator();

        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function getPerPageNumber(): int
    {
        return $this->limit;
    }

    public function getIterator()
    {
        return $this->iterator;
    }

    public function getCurrentPage()
    {
        $currentPage = ($this->offset / $this->limit) + 1;

        return $currentPage;
    }
}
