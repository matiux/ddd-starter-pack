<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Domain\Aggregate;

use DDDStarterPack\Domain\Aggregate\DomainCollection;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Support\Model\PersonId;

class DomainCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function use_domain_collection(): void
    {
        $people = new DomainCollection([Person::crea(PersonId::create(), 'Mat', 34)]);
        $people->add(Person::crea(PersonId::create(), 'Teo', 85));

        // Psalm gets angry - as it should be
        // $collection->add(new \stdClass());

        self::assertCount(2, $people);
    }

    /**
     * @test
     */
    public function create_empty(): void
    {
        /** @var DomainCollection<Person> $people */
        $people = new DomainCollection();

        // Psalm gets angry - as it should be
        //$collection->add(new \stdClass());

        $people->add(Person::crea(PersonId::create(), 'Mat', 34));

        self::assertCount(1, $people);
    }

    /**
     * @test
     */
    public function loop_over(): void
    {
        /**
         * @psalm-var DomainCollection<Person> $people
         */
        $people = new DomainCollection();

        $people->add(Person::crea(PersonId::create(), 'Mat', 34));
        $people->add(Person::crea(PersonId::create(), 'Teo', 85));

        $i = 0;

        foreach ($people as $person) {
            ++$i;
        }

        self::assertEquals(2, $i);
    }
}
