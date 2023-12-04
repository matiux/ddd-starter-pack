<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity;

/**
 * @template T
 *
 * @implements GenericId<T>
 */
abstract readonly class BasicGenericId implements GenericId, \JsonSerializable
{
    /**
     * @param T $id
     */
    final protected function __construct(private mixed $id) {}

    public function equals(GenericId $entityId, bool $string = true): bool
    {
        return $string ?
            $this == $entityId :
            $this->value() == $entityId->value();
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

    /**
     * @return T
     */
    public function jsonSerialize(): mixed
    {
        return $this->id;
    }
}
