<?php

namespace DDDStarterPack\Domain\Aggregate;

interface EntityId
{
    public static function create($anId = false): EntityId;

    public function id(): ?string;

    public function equals(EntityId $entityId): bool;

    public function __toString();
}
