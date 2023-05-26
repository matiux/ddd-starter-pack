<?php

declare(strict_types=1);

namespace DDDStarterPack\DataTransformer;

use DDDStarterPack\DataTransformer\Type\DataTransformer;
use DDDStarterPack\DataTransformer\Type\ItemDataTransformer;

/**
 * @template I
 * @template R
 *
 * @implements ItemDataTransformer<I, R>
 */
abstract class BasicItemDataTransformer implements ItemDataTransformer
{
    /** @var I */
    protected $item;

    /**
     * @param I $item
     *
     * @return static<I, R>
     */
    public function write($item): DataTransformer
    {
        $this->item = $item;

        return $this;
    }
}
