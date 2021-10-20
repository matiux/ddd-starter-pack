<?php

declare(strict_types=1);

namespace DDDStarterPack\Command;

use DateTimeImmutable;

interface DomainCommand
{
    public function occurredAt(): DateTimeImmutable;
}
