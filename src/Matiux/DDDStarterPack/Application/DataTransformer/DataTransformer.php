<?php

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Domain\Model\IdentifiableDomainObject;
use DDDStarterPack\Domain\Model\Repository\Paginator\Paginator;

interface DataTransformer
{
    /**
     * @param IdentifiableDomainObject|array $item
     * @return DataTransformer
     */
    public function write($item): DataTransformer;

    /**
     * @param \Traversable|array|Paginator $collection
     * @param int $total
     * @return DataTransformer
     */
    public function writeCollection($collection, int $total = null): DataTransformer;

    /**
     * @return mixed
     */
    public function read();
}
