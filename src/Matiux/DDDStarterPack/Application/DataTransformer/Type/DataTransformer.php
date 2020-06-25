<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer\Type;

/**
 * @template R
 */
interface DataTransformer
{
    /**
     * @return R
     */
    public function read();
}
