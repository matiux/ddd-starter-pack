<?php

declare(strict_types=1);

namespace DDDStarterPack\DataTransformer\Type;

use DDDStarterPack\Repository\Paginator\PaginatorI;

/**
 * @template I
 * @template R
 *
 * @extends DataTransformer<R>
 */
interface PaginatorDataTransformer extends DataTransformer
{
    /**
     * @param PaginatorI<I> $items
     *
     * @return static
     */
    public function write(PaginatorI $items): DataTransformer;
}
