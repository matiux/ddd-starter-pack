<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Event;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredAt(): DateTimeImmutable;
}
