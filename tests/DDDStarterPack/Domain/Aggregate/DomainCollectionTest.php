<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Domain\Aggregate;

use DDDStarterPack\Domain\Aggregate\DomainCollection;
use PHPUnit\Framework\TestCase;
use stdClass;
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
        /** @psalm-var DomainCollection<Person> $people */
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

        foreach ($people as $_person) {
            ++$i;
        }

        self::assertEquals(2, $i);
    }

    /**
     * @test
     */
    public function should_merge_two_collections(): void
    {
        /**
         * @psalm-var DomainCollection<Person> $people1
         */
        $people1 = new DomainCollection();
        $people1->add(Person::crea(PersonId::create(), 'Mat', 34));

        /**
         * @psalm-var DomainCollection<Person> $people2
         */
        $people2 = new DomainCollection();
        $people2->add(Person::crea(PersonId::create(), 'Teo', 85));

        $people = $people1->merge($people2);

        self::assertCount(2, $people);

        $mat = $people->current();
        $people->next();

        $teo = $people->current();

        self::assertSame('Mat', $mat->name());
        self::assertSame('Teo', $teo->name());
    }

    /**
     * @test
     */
    public function use_specific_collection(): void
    {
        $collection = new SpecificCollection();
        //$collection->add('Foo'); // Psalm si arrabbia, giustamente
        $collection->add(new stdClass());

        self::assertCount(1, $collection);
    }
}

/**
 * @extends DomainCollection<stdClass>
 */
class SpecificCollection extends DomainCollection
{
}
