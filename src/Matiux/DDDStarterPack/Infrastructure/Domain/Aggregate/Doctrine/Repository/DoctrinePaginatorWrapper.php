<?php

namespace DDDStarterPack\Infrastructure\Domain\Aggregate\Doctrine\Repository;

use DDDStarterPack\Domain\Aggregate\Repository\Paginator\PaginatorWrapper;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrinePaginatorWrapper extends PaginatorWrapper
{
    /** @var  Paginator */
    protected $aggregate;

    public function count()
    {
        return $this->getTotalResult();
    }

    public function getCurrentPageCollection()
    {
        if (!($this->iterator instanceof \ArrayIterator)) {

            throw new \UnexpectedValueException('Iterator must be an instance of \ArrayIterator');
        }

        $collection = $this->iterator->getArrayCopy();

        return $collection;
    }

    public function getTotalPage(): int
    {
        $tot = $this->getTotalResult();

        $totalPage = (int)ceil($tot / $this->limit);

        return $totalPage;
    }

    public function getTotalResult(): int
    {
        return $this->aggregate->count();
    }
}
