<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain;

/**
 * @template T
 * @implements EntityId<T>
 */
abstract class BasicEntityId implements EntityId
{
    /**
     * @param T $id
     */
    final protected function __construct(private $id)
    {
    }

    public function equals(EntityId $entityId): bool
    {
        return $this->value() === $entityId->value();
    }

    /**
     * @return T
     */
    public function value()
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->value();
    }
}
