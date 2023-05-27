<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity;

use Ramsey\Uuid\Uuid as RamseyUuid;

class UuidV6 extends Uuid
{
    public static function new(): static
    {
        return new static(RamseyUuid::uuid6()->toString());
    }
}
