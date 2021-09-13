<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate\Repository\Paginator;

use ArrayIterator;
use ArrayObject;

/**
 * @template I
 * @implements Paginator<I>
 */
abstract class AbstractPaginator implements Paginator
{
    /** @var ArrayIterator<int, I> */
    private ArrayIterator $iterator;

    /**
     * @param ArrayObject<int, I> $page
     * @param int                 $offset
     * @param int                 $limit
     * @param int                 $totalResult
     */
    public function __construct(
        private ArrayObject $page,
        private int $offset,
        private int $limit,
        private int $totalResult,
    ) {
        $this->iterator = $page->getIterator();
    }

    /** @return I */
    public function current()
    {
        return $this->iterator->current();
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    public function key(): int
    {
        return $this->iterator->key();
    }

    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    public function rewind(): void
    {
        $this->iterator->rewind();
    }

    public function count(): int
    {
        return $this->page->count();
    }

    /** @return array<int, I> */
    public function getCurrentPageCollection(): array
    {
        return $this->page->getArrayCopy();
    }

    public function getCurrentPage(): int
    {
        return 0 === $this->limit ? 1 : intval($this->offset / $this->limit) + 1;
    }

    public function getNumberOfPages(): int
    {
        $tot = $this->getTotalResult();

        return (int) ceil($tot / $this->limit);
    }

    public function getTotalResult(): int
    {
        return $this->totalResult;
    }

    public function getPerPageNumber(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
