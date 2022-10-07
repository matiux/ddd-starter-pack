<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain;

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
     * @return I
     */
    public function value();

    public function equals(EntityId $entityId): bool;
}
