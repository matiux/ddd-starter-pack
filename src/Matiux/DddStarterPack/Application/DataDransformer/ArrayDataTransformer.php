<?php

namespace DddStarterPack\Application\DataTtansformer;

abstract class ArrayDataTransformer implements DataTransformer
{
    /**
     * @param \Traversable $domainModelCollection
     * @param int $total
     * @return DataTransformer
     */
    public function writeCollection(\Traversable $domainModelCollection, int $total = null): DataTransformer
    {
        throw new \BadMethodCallException('If you need to transform a collection, use ArrayCollectionDataTransformer class', 500);
    }
}
