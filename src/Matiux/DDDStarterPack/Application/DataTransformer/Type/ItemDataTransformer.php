<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer\Type;

/**
 * @template I
 * @template R
 * @extends DataTransformer<R>
 */
interface ItemDataTransformer extends DataTransformer
{
    /**
     * @param I $item
     *
     * @return static
     */
    public function write($item);
}
