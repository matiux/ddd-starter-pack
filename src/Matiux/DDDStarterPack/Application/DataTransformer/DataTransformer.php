<?php

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Domain\Model\IdentifiableDomainObject;

interface DataTransformer
{
    /**
     * @param IdentifiableDomainObject $item
     * @return DataTransformer
     */
    public function write($item): DataTransformer;

    public function read();
}
