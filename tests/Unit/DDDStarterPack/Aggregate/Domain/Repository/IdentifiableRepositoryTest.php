<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Aggregate\Domain\Repository;

use DDDStarterPack\Aggregate\Domain\Repository\IdentifiableRepository;
use PHPUnit\Framework\TestCase;
use Tests\Support\Model\Person;
use Tests\Support\Model\PersonId;

class IdentifiableRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function find_person_by_id(): void
    {
        $personId = PersonId::create();

        $repo = new InMemoryRepository($personId);

        // Psalm gets angry - as it should be
        //$repo->ofId(34);
        //$repo->ofId(null);

        $person = $repo->ofId($personId);

        self::assertNotNull($person);

        $personId = $repo->nextIdentity();
        self::assertFalse($personId->isNull());
    }
}

/**
 * @implements IdentifiableRepository<Person, PersonId>
 */
class InMemoryRepository implements IdentifiableRepository
{
    /** @var Person[] */
    private array $people = [];

    public function __construct(PersonId $personId)
    {
        $this->people[] = Person::crea($personId, 'Mat', 34);
    }

    public function ofId($id)
    {
        foreach ($this->people as $person) {
            if ($person->id()->equals($id)) {
                return $person;
            }
        }

        return null;
    }

    public function nextIdentity()
    {
        return PersonId::create();
    }
}
