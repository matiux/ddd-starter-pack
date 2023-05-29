<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity;

/**
 * @template T
 *
 * @implements GenericId<T>
 */
abstract readonly class BasicGenericId implements GenericId
{
    /**
     * @param T $id
     */
    final protected function __construct(private mixed $id)
    {
    }

    public function equals(GenericId $entityId): bool
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
