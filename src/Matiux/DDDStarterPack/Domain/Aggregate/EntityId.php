<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Aggregate;

interface EntityId
{
    public function __toString(): string;

    public static function create(): static;

    public static function createFrom(int|string $id): static;

    public static function createNull(): static;

    public function id(): null|int|string;

    public function equals(EntityId $entityId): bool;
}
