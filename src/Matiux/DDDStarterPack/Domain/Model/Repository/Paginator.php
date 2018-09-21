<?php

namespace DDDStarterPack\Domain\Model\Repository;

interface Paginator
{
    public function getCurrentPageCollection();

    public function getCurrentPage();

    public function getTotalPage(): int;

    public function getTotalResult(): int;

    public function getPerPageNumber(): int;

    public function getIterator();
}
