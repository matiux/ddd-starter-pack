<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Application\DataTransformer\Type\CollectionDataTransformer;

/**
 * @template I
 * @implements CollectionDataTransformer<I>
 */
abstract class BasicCollectionDataTransformer implements CollectionDataTransformer
{
    /** @var list<I> */
    protected $items;

    /** @var int */
    protected $total;

    /**
     * @param list<I> $items
     * @param int     $total
     *
     * @return CollectionDataTransformer
     */
    public function write($items, int $total = 0): CollectionDataTransformer
    {
        $this->items = $items;
        $this->total = $total;

        return $this;
    }
}
