<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Application\DataTransformer\Type\ItemDataTransformer;

/**
 * @template I
 * @implements ItemDataTransformer<I>
 */
abstract class BasicItemDataTransformer implements ItemDataTransformer
{
    /** @var I */
    protected $item;

    /**
     * @param I $item
     *
     * @return ItemDataTransformer<I>
     */
    public function write($item): ItemDataTransformer
    {
        $this->item = $item;

        return $this;
    }
}
