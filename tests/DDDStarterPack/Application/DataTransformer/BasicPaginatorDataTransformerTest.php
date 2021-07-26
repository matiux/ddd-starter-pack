<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Application\DataTransformer;

use ArrayObject;
use DDDStarterPack\Application\DataTransformer\BasicPaginatorDataTransformer;
use DDDStarterPack\Domain\Aggregate\Repository\Paginator\AbstractPaginator;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Support\Model\PersonId;

class BasicPaginatorDataTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function transform_paginator_of_person(): void
    {
        $dt = new PeoplePaginatorDataTransformer();

        $people = [
            Person::crea(PersonId::create(), 'Mat', 34),
            Person::crea(PersonId::create(), 'Teo', 28),
            Person::crea(PersonId::create(), 'Iux', 85),
            Person::crea(PersonId::create(), 'Foo', 43),
        ];

        // Psalm gets angry - as it should be
        // $people = [
        //     new \stdClass(),
        //     new \stdClass(),
        // ];

        $transformed = $dt->write(new PeoplePaginator(new ArrayObject($people), 0, 3, 4))->read();

        self::assertNotEmpty($transformed);
        self::assertCount(2, $transformed);

        self::assertTrue(isset($transformed['meta']));
        self::assertTrue(isset($transformed['meta']['total']));
        self::assertTrue(isset($transformed['meta']['per_page']));
        self::assertTrue(isset($transformed['meta']['page']));
        self::assertTrue(isset($transformed['meta']['total_pages']));

        $meta = [
            'total' => 4,
            'per_page' => 3,
            'page' => 1,
            'total_pages' => 2,
        ];
        self::assertSame($meta, $transformed['meta']);

        self::assertTrue(isset($transformed['data']));
        self::assertIsArray($transformed['data']);
        self::assertNotEmpty($transformed['data']);
        self::assertCount(4, $transformed['data']);
    }
}

/**
 * @extends BasicPaginatorDataTransformer<Person>
 */
class PeoplePaginatorDataTransformer extends BasicPaginatorDataTransformer
{
    /**
     * @return class-string<PersonDataTransformer>
     */
    protected function getSingleTransformerNs(): string
    {
        return PersonDataTransformer::class;
    }
}

/**
 * @extends AbstractPaginator<Person>
 */
class PeoplePaginator extends AbstractPaginator
{
}
