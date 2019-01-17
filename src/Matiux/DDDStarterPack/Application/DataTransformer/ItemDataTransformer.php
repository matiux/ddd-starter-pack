<?php

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Domain\Model\IdentifiableDomainObject;

abstract class ItemDataTransformer implements DataTransformer
{
    protected $item;

    /**
     * @param IdentifiableDomainObject $item
     * @return DataTransformer
     */
    public function write($item): DataTransformer
    {
        $this->item = $item;

        return $this;
    }
}
