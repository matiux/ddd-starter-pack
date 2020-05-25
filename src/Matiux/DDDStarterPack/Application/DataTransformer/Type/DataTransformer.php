<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer\Type;

interface DataTransformer
{
    /**
     * @template R
     *
     * @return R
     */
    public function read();
}
