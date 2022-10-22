<?php

declare(strict_types=1);

namespace DDDStarterPack\Aggregate\Domain\Identifier;

use Ramsey\Uuid\Uuid;

class UuidV4EntityId extends UuidEntityId
{
    public static function create(): static
    {
        return new static(Uuid::uuid4()->toString());
    }
}
