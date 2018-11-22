<?php

namespace DDDStarterPack\Application\DataTransformer;

abstract class ItemDataTransformer implements DataTransformer
{
    protected $model;

    public function writeCollection($collection, int $total = null): DataTransformer
    {
        throw new \BadMethodCallException('If you need to transform a collection, use ArrayCollectionDataTransformer class', 500);
    }
}
