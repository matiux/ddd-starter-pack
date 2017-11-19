<?php

namespace DddStarterPack\Domain\Model;

interface EntityId
{
    public static function create(?string $anId = null): EntityId;

    public function id(): string;

    public function equals(IdentifiableDomainObject $entity): bool;

    public function __toString();
}
