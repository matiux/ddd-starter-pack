<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\DataTransformer\Type;

use DDDStarterPack\Domain\Aggregate\Repository\Paginator\Paginator;

/**
 * @template I
 * @extends DataTransformer
 */
interface PaginatorDataTransformer extends DataTransformer
{
    /**
     * @param Paginator<I> $items
     *
     * @return PaginatorDataTransformer
     */
    public function write(Paginator $items): PaginatorDataTransformer;
}
