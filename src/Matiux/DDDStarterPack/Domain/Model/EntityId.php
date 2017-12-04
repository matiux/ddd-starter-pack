<?php

namespace DDDStarterPack\Domain\Model;

interface EntityId
{
    public static function create(?string $anId = null): EntityId;

    public function id(): string;

    public function equals(EntityId $entityId): bool;

    public function __toString();
}
