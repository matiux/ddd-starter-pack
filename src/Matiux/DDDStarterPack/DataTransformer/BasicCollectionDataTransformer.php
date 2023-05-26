<?php

declare(strict_types=1);

namespace DDDStarterPack\DataTransformer;

use DDDStarterPack\DataTransformer\Type\CollectionDataTransformer;
use DDDStarterPack\DataTransformer\Type\DataTransformer;

/**
 * @template I
 * @template R
 *
 * @implements CollectionDataTransformer<I, R>
 */
abstract class BasicCollectionDataTransformer implements CollectionDataTransformer
{
    /**
     * @var list<I>
     */
    protected array $items = [];
    protected int $total;

    public function __construct()
    {
        $this->total = 0;
    }

    /**
     * @param list<I> $items
     * @param int     $total
     *
     * @return static
     */
    public function write($items, int $total = 0): DataTransformer
    {
        $this->items = $items;
        $this->total = $total;

        return $this;
    }
}
