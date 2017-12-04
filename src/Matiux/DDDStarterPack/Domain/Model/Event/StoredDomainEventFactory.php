<?php

namespace DDDStarterPack\Domain\Model\Event;

interface StoredDomainEventFactory
{
    public function build(string $eventType, \DateTimeImmutable $occuredOn, string $serializedEvent): StoredDomainEvent;
}
