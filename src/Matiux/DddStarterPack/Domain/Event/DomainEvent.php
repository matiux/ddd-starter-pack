<?php

namespace DddStarterPack\Domain\Event;

interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}
