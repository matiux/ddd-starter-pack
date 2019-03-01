<?php

namespace DDDStarterPack\Application\DataTransformer;

abstract class ItemDataTransformer implements DataTransformer
{
    protected $item;

    public function write($item): DataTransformer
    {
        $this->item = $item;

        return $this;
    }
}
