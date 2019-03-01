<?php

namespace DDDStarterPack\Application\DataTransformer;

interface DataTransformer
{
    public function write($item): DataTransformer;

    public function read();
}
