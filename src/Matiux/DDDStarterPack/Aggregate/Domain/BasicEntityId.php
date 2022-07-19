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
    final protected function __construct(
        protected $id
    ) {
    }

    public function equals(EntityId $entityId): bool
    {
        return $this->id() === $entityId->id();
    }

    /**
     * @return T
     */
    public function id()
    {
        return $this->id;
    }
}
