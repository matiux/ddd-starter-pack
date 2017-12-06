<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\EventStore;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventFactory;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventInterface;

class InMemoryStoredDomainEventFactory implements StoredDomainEventFactory
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function build($eventID, string $eventType, \DateTimeImmutable $occuredOn, string $serializedEvent): StoredDomainEventInterface
    {
        $storedEvent = new FakeStoredEvent($this->eventStore->nextId(), $eventType, $occuredOn, $serializedEvent);

        return $storedEvent;
    }
}
