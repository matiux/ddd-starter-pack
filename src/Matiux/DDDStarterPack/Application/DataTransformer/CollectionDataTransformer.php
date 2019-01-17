<?php

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Domain\Model\Repository\Paginator\Paginator;

interface CollectionDataTransformer
{
    /**
     * @param \Traversable|array|Paginator $items
     * @param int $total
     * @return CollectionDataTransformer
     */
    public function writeCollection($items, int $total = null): CollectionDataTransformer;

    public function read();
}
