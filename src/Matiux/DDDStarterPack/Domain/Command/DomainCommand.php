<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Command;

use DateTimeImmutable;

interface DomainCommand
{
    public function occurredAt(): DateTimeImmutable;
}
