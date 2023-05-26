<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\DataTransformer;

use DDDStarterPack\DataTransformer\BasicCollectionDataTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Support\Model\PersonId;

class BasicCollectionDataTransformerTest extends TestCase
{
    /**
     * @test
     */
    public function transform_array_of_person(): void
    {
        $dt = new PeopleDataTransformer();

        $people = [
            Person::crea(PersonId::create(), 'Mat', 34),
            Person::crea(PersonId::create(), 'Teo', 28),
        ];

        $transformed = $dt->write($people)->read();

        // Psalm gets angry - as it should be
        // $transformed = $dt->writeCollection([new \stdClass()]);

        self::assertNotEmpty($transformed);
        self::assertCount(2, $transformed);
        self::assertTrue(isset($transformed[0]['name']));
        self::assertTrue(isset($transformed[0]['age']));
        self::assertArrayHasKey('name', $transformed[0]);
        self::assertSame('Mat', $transformed[0]['name']);
        self::assertSame(34, $transformed[0]['age']);
    }
}

/**
 * @extends BasicCollectionDataTransformer<Person, array>
 */
class PeopleDataTransformer extends BasicCollectionDataTransformer
{
    public function read(): array
    {
        $people = [];

        /** @var array<int, Person> $c */
        $c = $this->items;

        foreach ($c as $person) {
            $people[] = $person->toArray();
        }

        return $people;
    }
}
