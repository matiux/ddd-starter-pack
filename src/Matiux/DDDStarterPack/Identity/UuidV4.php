<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity;

use Ramsey\Uuid\Uuid as RamseyUuid;

readonly class UuidV4 extends Uuid
{
    public static function new(): static
    {
        return new static(RamseyUuid::uuid4()->toString());
    }
}
