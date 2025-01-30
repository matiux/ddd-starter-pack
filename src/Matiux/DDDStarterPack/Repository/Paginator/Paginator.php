<?php

declare(strict_types=1);

namespace DDDStarterPack\Repository\Paginator;

/**
 * @template I
 *
 * @implements PaginatorI<I>
 */
class Paginator implements PaginatorI
{
    /** @var \ArrayIterator<int, I> */
    private \ArrayIterator $iterator;

    /**
     * @param array<int, I> $page
     * @param int           $offset
     * @param int           $limit
     * @param int           $totalResult
     */
    public function __construct(
        private array $page,
        private int $offset,
        private int $limit,
        private int $totalResult,
    ) {
        $obj = (new \ArrayObject($page));

        $this->iterator = $obj->getIterator();
    }

    /** @return I */
    public function current(): mixed
    {
        return $this->iterator->current();
    }

    public function next(): void
    {
        $this->iterator->next();
    }

    public function key(): int
    {
        return $this->iterator->key() ?? throw new \LogicException('Key is not set');
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
        return count($this->page);
    }

    /**
     * @return array<int, I>
     */
    public function getCurrentPageCollection(): array
    {
        return $this->page;
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
