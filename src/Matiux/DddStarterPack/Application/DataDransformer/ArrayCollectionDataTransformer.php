<?php

namespace DddStarterPack\Application\DataTtansformer;

use DddStarterPack\Domain\Model\IdentifiableDomainObject;

abstract class ArrayCollectionDataTransformer implements DataTransformer
{
    protected $models = [];

    /**
     * @param IdentifiableDomainObject $domainModel
     * @return DataTransformer
     */
    public function write(IdentifiableDomainObject $domainModel): DataTransformer
    {
        throw new \BadMethodCallException('If you need to transform a single element, use ArrayDataTransformer::write class', 500);
    }
}
