<?php

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Domain\Model\IdentifiableDomainObject;

abstract class CollectionDataTransformer implements DataTransformer
{
    protected $models = [];

    public function write(IdentifiableDomainObject $domainModel): DataTransformer
    {
        throw new \BadMethodCallException('If you need to transform a single element, use ArrayDataTransformer::write class', 500);
    }
}
