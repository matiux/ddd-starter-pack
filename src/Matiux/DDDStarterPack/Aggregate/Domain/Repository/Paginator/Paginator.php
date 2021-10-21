<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Repository\Paginator;

use Countable;
use Iterator;

/**
 * @template I
 */
interface Paginator extends Countable, Iterator
{
    /**
     * @return array<int, I>
     */
    public function getCurrentPageCollection();

    public function getCurrentPage(): int;

    public function getNumberOfPages(): int;

    public function getTotalResult(): int;

    public function getPerPageNumber(): int;

    public function getOffset(): int;

    public function getLimit(): int;
}
