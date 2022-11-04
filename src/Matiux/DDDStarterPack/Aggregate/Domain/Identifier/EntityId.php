<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Identifier;

/**
 * @template I of mixed
 */
interface EntityId
{
    public static function create(): static;

    /**
     * @template T of I
     *
     * @param T $id
     *
     * @return static
     */
    public static function createFrom($id): static;

    /**
     * @template T of I
     *
     * @param null|T $id
     *
     * @return null|static
     */
    public static function tryCreateFrom($id): null|static;

    /**
     * @return I
     */
    public function value();

    public function equals(EntityId $entityId): bool;
}
