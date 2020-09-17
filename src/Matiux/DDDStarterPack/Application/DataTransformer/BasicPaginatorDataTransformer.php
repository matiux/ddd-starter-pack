<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Application\DataTransformer\Type\ItemDataTransformer;
use DDDStarterPack\Application\DataTransformer\Type\PaginatorDataTransformer;
use DDDStarterPack\Domain\Aggregate\Repository\Paginator\Paginator;

/**
 * @template I
 * @implements PaginatorDataTransformer<I, array>
 */
abstract class BasicPaginatorDataTransformer implements PaginatorDataTransformer
{
    /** @var array{data: list<array<array-key,mixed>|mixed>, meta: array<array-key,mixed>} */
    protected $paginationData = [
        'data' => [],
        'meta' => [
            'total' => 0,
            'per_page' => 0,
            'page' => 0,
            'total_pages' => 0,
        ],
    ];

    /** @var array<int, I> */
    protected $page = [];

    public function read(): array
    {
        foreach ($this->page as $item) {
            $ns = $this->getSingleTransformerNs();

            $deps = $this->getDeps();

            /** @var ItemDataTransformer $itemDataTransformer */
            $itemDataTransformer = empty($deps) ?
                new $ns() :
                new $ns(...$this->getDeps());

            $this->paginationData['data'][] = $itemDataTransformer->write($item)->read();
        }

        return $this->paginationData;
    }

    /**
     * @psalm-return class-string<ItemDataTransformer>
     */
    abstract protected function getSingleTransformerNs(): string;

    protected function getDeps(): array
    {
        return [];
    }

    /**
     * @param Paginator<I> $items
     *
     * @return static
     */
    public function write(Paginator $items)
    {
        $this->paginationData['data'] = [];

        $this->paginationData['meta']['total'] = $items->getTotalResult();
        $this->paginationData['meta']['per_page'] = $items->getPerPageNumber();
        $this->paginationData['meta']['page'] = $items->getCurrentPage();
        $this->paginationData['meta']['total_pages'] = $items->getNumberOfPages();

        $this->page = $items->getCurrentPageCollection();

        return $this;
    }
}
