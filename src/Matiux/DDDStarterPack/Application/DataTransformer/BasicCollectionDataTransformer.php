<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Application\DataTransformer\Type\CollectionDataTransformer;

/**
 * @template I
 * @template R
 * @implements CollectionDataTransformer<I, R>
 */
abstract class BasicCollectionDataTransformer implements CollectionDataTransformer
{
    /**
     * @psalm-suppress PropertyNotSetInConstructor
     *
     * @var list<I>
     */
    protected $items;

    /** @var int */
    protected $total;

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
    public function write($items, int $total = 0)
    {
        $this->items = $items;
        $this->total = $total;

        return $this;
    }
}
