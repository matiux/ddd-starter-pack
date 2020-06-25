<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer\Type;

use DDDStarterPack\Domain\Aggregate\Repository\Paginator\Paginator;

/**
 * @template I
 * @template R
 * @extends DataTransformer<R>
 */
interface PaginatorDataTransformer extends DataTransformer
{
    /**
     * @param Paginator<I> $items
     *
     * @return static
     */
    public function write(Paginator $items);
}
