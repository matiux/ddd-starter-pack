<?php

namespace DDDStarterPack\Domain\Model\Event;

interface StoredDomainEventFactory
{
    public function build($eventID, string $eventType, \DateTimeImmutable $occuredOn, string $serializedEvent): StoredDomainEventInterface;
}
