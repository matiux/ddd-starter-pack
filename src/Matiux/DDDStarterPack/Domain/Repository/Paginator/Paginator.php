<?php

namespace DDDStarterPack\Domain\Aggregate\Repository\Paginator;

interface Paginator
{
    public function getCurrentPageCollection();

    public function getCurrentPage();

    public function getTotalPage(): int;

    public function getTotalResult(): int;

    public function getPerPageNumber(): int;

    public function getIterator();
}
