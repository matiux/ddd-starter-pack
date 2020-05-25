<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer\Type;

/**
 * @template I
 * @extends DataTransformer
 */
interface CollectionDataTransformer extends DataTransformer
{
    /**
     * @param list<I> $items
     * @param int     $total
     *
     * @return CollectionDataTransformer
     */
    public function write($items, int $total = 0): CollectionDataTransformer;
}
