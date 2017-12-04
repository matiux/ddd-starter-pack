<?php

namespace DDDStarterPack\Domain\Model\Event;

interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}
