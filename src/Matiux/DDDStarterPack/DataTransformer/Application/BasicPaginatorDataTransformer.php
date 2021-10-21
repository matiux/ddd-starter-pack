<?php

declare(strict_types=1);

namespace DDDStarterPack\DataTransformer\Application;

use DDDStarterPack\Aggregate\Domain\Repository\Paginator\Paginator;
use DDDStarterPack\DataTransformer\Application\Type\DataTransformer;
use DDDStarterPack\DataTransformer\Application\Type\ItemDataTransformer;
use DDDStarterPack\DataTransformer\Application\Type\PaginatorDataTransformer;

/**
 * @template I
 * @implements PaginatorDataTransformer<I, array>
 */
abstract class BasicPaginatorDataTransformer implements PaginatorDataTransformer
{
    /** @var array{data: list<array|mixed>, meta: array} */
    protected array $paginationData = [
        'data' => [],
        'meta' => [
            'total' => 0,
            'per_page' => 0,
            'page' => 0,
            'total_pages' => 0,
        ],
    ];

    /** @var array<int, I> */
    protected array $page = [];

    public function read(): array
    {
        foreach ($this->page as $item) {
            $ns = $this->getSingleTransformerNs();

            $deps = $this->getDeps();

            $itemDataTransformer = empty($deps) ?
                new $ns() :
                new $ns(...$deps);

            $this->paginationData['data'][] = $itemDataTransformer->write($item)->read();
        }

        return $this->paginationData;
    }

    /**
     * @param Paginator<I> $items
     *
     * @return static
     */
    public function write(Paginator $items): DataTransformer
    {
        $this->paginationData['data'] = [];

        $this->paginationData['meta']['total'] = $items->getTotalResult();
        $this->paginationData['meta']['per_page'] = $items->getPerPageNumber();
        $this->paginationData['meta']['page'] = $items->getCurrentPage();
        $this->paginationData['meta']['total_pages'] = $items->getNumberOfPages();

        $this->page = $items->getCurrentPageCollection();

        return $this;
    }

    /**
     * @psalm-return class-string<ItemDataTransformer>
     */
    abstract protected function getSingleTransformerNs(): string;

    protected function getDeps(): array
    {
        return [];
    }
}
