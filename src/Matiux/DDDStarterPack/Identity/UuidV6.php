<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity;

use Override;
use Ramsey\Uuid\Uuid as RamseyUuid;

readonly class UuidV6 extends Uuid
{
    #[Override]
    public static function new(): static
    {
        return new static(RamseyUuid::uuid6()->toString());
    }
}
