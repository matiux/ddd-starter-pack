<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Repository\Doctrine;

use Doctrine\ORM\Tools\Pagination\Paginator;
use DDDStarterPack\Domain\Model\Repository\Paginator\PaginatorWrapper;

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
