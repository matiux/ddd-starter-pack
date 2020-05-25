<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate;

interface EntityId
{
    public function __toString(): string;

    /** @return static */
    public static function create(): EntityId;

    /**
     * @param int|string $id
     *
     * @return static
     */
    public static function createFrom($id): EntityId;

    public static function createNUll(): EntityId;

    /**
     * @return null|int|string
     */
    public function id();

    /**
     * @param EntityId $entityId
     *
     * @return bool
     */
    public function equals($entityId): bool;
}
