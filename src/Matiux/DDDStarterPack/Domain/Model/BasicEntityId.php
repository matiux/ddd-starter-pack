<?php

namespace DDDStarterPack\Domain\Model;

use Ramsey\Uuid\Uuid;

abstract class BasicEntityId implements EntityId
{
    private $id;

    protected function __construct($anId = false)
    {
        $this->verifyInputId($anId);

        if (false === $anId) {

            $this->id = Uuid::uuid4()->toString();

        } else if (null === $anId) {

            $this->id = null;

        } else {

            $this->id = $anId;
        }
    }

    public static function create($anId = false): EntityId
    {
        return new static($anId);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(EntityId $entityId): bool
    {
        return $this->id() === $entityId->id();
    }

    public function isNull(): bool
    {
        return is_null($this->id);
    }

    private function verifyInputId($anId)
    {
        if (is_object($anId)) {
            throw new \InvalidArgumentException("Entity id input must be scalar type");
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id();
    }
}
