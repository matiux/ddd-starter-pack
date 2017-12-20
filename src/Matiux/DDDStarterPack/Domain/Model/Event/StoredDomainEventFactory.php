<?php

namespace DDDStarterPack\Domain\Model\Event;

interface StoredDomainEventFactory
{
    public function build($eventID, string $eventType, \DateTimeImmutable $occurredOn, string $serializedEvent): StoredDomainEventInterface;
}
