<?php

declare(strict_types=1);

namespace DDDStarterPack\DataTransformer\Application\Type;

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
