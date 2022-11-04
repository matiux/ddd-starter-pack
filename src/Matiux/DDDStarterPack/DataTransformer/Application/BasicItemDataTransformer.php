<?php

declare(strict_types=1);

namespace DDDStarterPack\DataTransformer\Application;

use DDDStarterPack\DataTransformer\Application\Type\DataTransformer;
use DDDStarterPack\DataTransformer\Application\Type\ItemDataTransformer;

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
