<?php

namespace DDDStarterPack\Application\DataTransformer;

abstract class ArrayDataTransformer implements DataTransformer
{
    protected $model;

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
