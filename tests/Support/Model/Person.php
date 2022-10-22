<?php

declare(strict_types=1);

namespace Tests\Support\Model;

class Person
{
    private $personId;
    private $name;
    private $age;

    private function __construct(PersonId $personId, string $name, int $age)
    {
        $this->personId = $personId;
        $this->name = $name;
        $this->age = $age;
    }

    public static function crea(PersonId $personId, string $name, int $age): self
    {
        return new self($personId, $name, $age);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'age' => $this->age,
        ];
    }

    public function id(): PersonId
    {
        return $this->personId;
    }
}
