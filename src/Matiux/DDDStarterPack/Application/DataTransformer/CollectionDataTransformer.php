<?php

namespace DDDStarterPack\Application\DataTransformer;

abstract class CollectionDataTransformer implements DataTransformer
{
    protected $models = [];

    public function write($item): DataTransformer
    {
        throw new \BadMethodCallException('If you need to transform a single element, use ArrayDataTransformer::write class', 500);
    }
}
