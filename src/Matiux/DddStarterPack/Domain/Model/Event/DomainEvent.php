<?php

namespace DddStarterPack\Domain\Model\Event;

interface DomainEvent
{
    public function occurredOn(): \DateTimeImmutable;
}
