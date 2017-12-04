<?php

namespace Tests\DDDStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory;

use DDDStarterPack\Domain\Model\Event\EventStore;
use DDDStarterPack\Domain\Model\Event\StoredDomainEvent;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventFactory;
use Tests\DDDStarterPack\Fake\Domain\Model\Event\StoredEvent;

class InMemoryStoredDomainEventFactory implements StoredDomainEventFactory
{
    private $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    public function build(string $eventType, \DateTimeImmutable $occuredOn, string $serializedEvent): StoredDomainEvent
    {
        $storedEvent = new StoredEvent($this->eventStore->nextId(), $eventType, $occuredOn, $serializedEvent);

        return $storedEvent;
    }
}
