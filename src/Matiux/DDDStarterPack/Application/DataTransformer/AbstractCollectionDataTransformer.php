<?php

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Domain\Model\Repository\Paginator\Paginator;

abstract class AbstractCollectionDataTransformer implements CollectionDataTransformer
{
    protected $items = [];
    protected $total;

    /**
     * @param \Traversable|array|Paginator $items
     * @param int $total
     * @return CollectionDataTransformer
     */
    public function writeCollection($items, int $total = null): CollectionDataTransformer
    {
        $this->items = $items;
        $this->total = $total;

        return $this;
    }
}
