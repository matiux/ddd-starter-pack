<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\DataTransformer;

use DDDStarterPack\DataTransformer\BasicPaginatorDataTransformer;
use DDDStarterPack\Repository\Paginator\Paginator;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Support\Model\PersonId;

class BasicPaginatorDataTransformerTest extends TestCase
{
    /**
     * @psalm-suppress all
     *
     * @test
     */
    public function transform_paginator_of_person(): void
    {
        $dt = new PeoplePaginatorDataTransformer();

        $people = [
            Person::crea(PersonId::new(), 'Mat', 34),
            Person::crea(PersonId::new(), 'Teo', 28),
            Person::crea(PersonId::new(), 'Iux', 85),
            Person::crea(PersonId::new(), 'Foo', 43),
        ];

        // Psalm gets angry - as it should be
        // $people = [
        //     new \stdClass(),
        //     new \stdClass(),
        // ];

        $transformed = $dt->write(new Paginator($people, 0, 3, 4))->read();

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
