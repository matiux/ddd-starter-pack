<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository\Paginator;

use ArrayObject;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\PersonPaginator;

class AbstractPaginatorTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_be_iterable(): void
    {
        $items = new ArrayObject([0, 1, 1, 2, 3, 5]);

        $paginator = new PersonPaginator(
            page: $items,
            offset: 0,
            limit: 3,
            totalResult: 6
        );

        self::assertCount($paginator->getTotalResult(), $paginator);

        foreach ($paginator as $i => $value) {
            self::assertIsInt($value);
            self::assertSame($i, $paginator->key());
        }

        self::assertSame(0, $paginator->getOffset());
        self::assertSame(3, $paginator->getLimit());
    }
}
