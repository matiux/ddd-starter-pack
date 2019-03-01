<?php

namespace DDDStarterPack\Domain\Aggregate\Event;

use DateTimeImmutable;

interface DomainEvent
{
    public function occurredAt(): DateTimeImmutable;
}
