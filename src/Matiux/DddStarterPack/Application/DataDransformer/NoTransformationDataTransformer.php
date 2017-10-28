<?php

namespace DddStarterPack\Application\DataTtansformer;

use DddStarterPack\Domain\Model\IdentifiableDomainObject;

abstract class NoTransformationDataTransformer implements DataTransformer
{
    protected $data;

    /**
     * @param IdentifiableDomainObject $domainModel
     * @return DataTransformer
     */
    public function write(IdentifiableDomainObject $domainModel): DataTransformer
    {
        $this->data = $domainModel;
    }

    /**
     * @param \Traversable $domainModelCollection
     * @param int $total
     * @return DataTransformer
     */
    public function writeCollection(\Traversable $domainModelCollection, int $total = null): DataTransformer
    {
        $this->data = $domainModelCollection;
    }

    /**
     * @return mixed
     */
    public function read()
    {
        return $this->data;
    }
}
