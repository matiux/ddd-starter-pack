<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer\Type;

/**
 * @template I
 * @extends DataTransformer
 */
interface ItemDataTransformer extends DataTransformer
{
    /**
     * @param I $item
     *
     * @return ItemDataTransformer
     */
    public function write($item): ItemDataTransformer;
}
