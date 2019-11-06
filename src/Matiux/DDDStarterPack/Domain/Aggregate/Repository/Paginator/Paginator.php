<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\Paginator;

use Countable;
use IteratorAggregate;

interface Paginator extends Countable, IteratorAggregate
{
    public function getCurrentPageCollection();

    public function getCurrentPage();

    public function getTotalPage(): int;

    public function getTotalResult(): int;

    public function getPerPageNumber(): int;

    public function getIterator();
}
