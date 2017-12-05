<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\EventStore;
use DDDStarterPack\Domain\Model\Event\StoredDomainEvent;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventFactory;

class InMemoryStoredDomainEventFactory implements StoredDomainEventFactory
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function build($ventID, string $eventType, \DateTimeImmutable $occuredOn, string $serializedEvent): StoredDomainEvent
    {
        $storedEvent = new FakeStoredEvent($this->eventStore->nextId(), $eventType, $occuredOn, $serializedEvent);

        return $storedEvent;
    }
}
