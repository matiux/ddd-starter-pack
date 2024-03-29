<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Paginator;

use Iterator;

/**
 * @template I
 *
 * @extends Iterator<int, I>
 */
interface PaginatorI extends \Countable, Iterator
{
    /**
     * @return array<int, I>
     */
    public function getCurrentPageCollection(): array;

    public function getCurrentPage(): int;

    public function getNumberOfPages(): int;

    public function getTotalResult(): int;

    public function getPerPageNumber(): int;

    public function getOffset(): int;

    public function getLimit(): int;
}
