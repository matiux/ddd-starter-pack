<?php

namespace DDDStarterPack\Application\DataTransformer;

use Assert\Assertion;
use DDDStarterPack\Domain\Model\Repository\Paginator\Paginator;

abstract class AbstractPaginatorDataTransformer implements CollectionDataTransformer
{
    protected $paginationData = [
        'meta' => [
            'total' => 0,
            'per_page' => 0,
            'page' => 0,
            'total_pages' => 0,
        ],
        'data' => []
    ];

    /** @var Paginator */
    protected $paginator;

    /**
     * @param \Traversable|array|Paginator $items
     * @param int $total
     * @return CollectionDataTransformer
     */
    public function writeCollection($items, int $total = null): CollectionDataTransformer
    {
        Assertion::isInstanceOf($items, Paginator::class);

        $this->paginationData['data'] = [];

        $this->paginationData['meta']['total'] = $items->getTotalResult();
        $this->paginationData['meta']['per_page'] = $items->getPerPageNumber();
        $this->paginationData['meta']['page'] = $items->getCurrentPage();
        $this->paginationData['meta']['total_pages'] = $items->getTotalPage();

        $this->paginator = $items;

        return $this;
    }

    public function read(): array
    {
        foreach ($this->paginator->getIterator() as $item) {

            $ns = $this->getSingleTransformerNs();
            $this->paginationData['data'][] = (new $ns)->write($item)->read();
        }

        return $this->paginationData;
    }

    protected abstract function getSingleTransformerNs(): string;
}
