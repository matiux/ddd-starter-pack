<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Paginator;

use DDDStarterPack\Aggregate\Domain\Repository\Paginator\Paginator;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        $items = new \ArrayObject([0, 1, 1, 2, 3, 5]);

        $paginator = new Paginator(
            page: $items,
            offset: 0,
            limit: 3,
            totalResult: 6
        );

        self::assertCount($paginator->getTotalResult(), $paginator);

        foreach ($paginator as $i => $value) {
            self::assertSame($i, $paginator->key());
            self::assertSame($value, $paginator->current());
        }

        self::assertSame(0, $paginator->getOffset());
        self::assertSame(3, $paginator->getLimit());
    }
}
