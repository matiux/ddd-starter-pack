<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer;

use DDDStarterPack\Application\DataTransformer\Type\ItemDataTransformer;

/**
 * @template I
 * @template R
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
    public function write($item)
    {
        $this->item = $item;

        return $this;
    }
}
