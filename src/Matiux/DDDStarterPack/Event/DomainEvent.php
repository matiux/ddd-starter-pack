<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredAt(): DateTimeImmutable;

    public function id(): string;
}
