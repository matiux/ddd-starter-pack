<?php

namespace DddStarterPack\Application\DataTtansformer;

use DddStarterPack\Domain\Model\IdentifiableDomainObject;

interface DataTransformer
{
    /**
     * @param IdentifiableDomainObject $domainModel
     * @return DataTransformer
     */
    public function write(IdentifiableDomainObject $domainModel): DataTransformer;

    /**
     * @param \Traversable $domainModelCollection
     * @param int $total
     * @return DataTransformer
     */
    public function writeCollection(\Traversable $domainModelCollection, int $total = null): DataTransformer;

    /**
     * @return mixed
     */
    public function read();
}
