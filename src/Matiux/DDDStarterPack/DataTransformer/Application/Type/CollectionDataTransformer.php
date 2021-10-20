<?php

declare(strict_types=1);

namespace DDDStarterPack\DataTransformer\Application\Type;

/**
 * @template I
 * @template R
 * @extends DataTransformer<R>
 */
interface CollectionDataTransformer extends DataTransformer
{
    /**
     * @param list<I> $items
     * @param int     $total
     *
     * @return static
     */
    public function write($items, int $total = 0): DataTransformer;
}
