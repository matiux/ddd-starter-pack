<?php

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Domain\Model\Repository\Paginator\Paginator;

abstract class PaginatorDataTransformer implements DataTransformer
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
     * @param Paginator $paginator
     * @param int|null $total
     * @return DataTransformer
     */
    public function writeCollection($paginator, int $total = null): DataTransformer
    {
        $this->paginationData['data'] = [];

        $this->paginationData['meta']['total'] = $paginator->getTotalResult();
        $this->paginationData['meta']['per_page'] = $paginator->getPerPageNumber();
        $this->paginationData['meta']['page'] = $paginator->getCurrentPage();
        $this->paginationData['meta']['total_pages'] = $paginator->getTotalPage();

        $this->paginator = $paginator;

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

    public function write($item): DataTransformer
    {
        throw new \BadMethodCallException('If you need to transform a single element, use ArrayDataTransformer::write class', 500);
    }

    protected abstract function getSingleTransformerNs(): string;
}
