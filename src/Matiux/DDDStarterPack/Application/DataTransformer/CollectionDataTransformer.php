<?php

namespace DDDStarterPack\Application\DataTransformer;

interface CollectionDataTransformer
{
    public function writeCollection($items, int $total = null): CollectionDataTransformer;

    public function read();
}
