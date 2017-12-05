<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\DomainEvent;
use DDDStarterPack\Domain\Model\Event\EventStore;
use DDDStarterPack\Domain\Model\Event\StoredDomainEvent;

class InMemoryEventStore implements EventStore
{
    private $events = [];

    public function allStoredEventsSince(?int $anEventId, ?int $limit = null): \ArrayObject
    {
        $events = array_filter($this->events, function (StoredDomainEvent $storedEvent) use ($anEventId) {

            return $storedEvent->eventId() > $anEventId;
        });

        return new \ArrayObject($events);
    }

    public function nextId(): int
    {
        $greatesId = 0;

        array_walk($this->events, function (StoredDomainEvent $storedEvent) use (&$greatesId) {

            $greatesId = $storedEvent->eventId() > $greatesId ? $storedEvent->eventId() : $greatesId;

        });

        return $greatesId + 1;
    }

    public function add(DomainEvent $storedEvent)
    {
        $this->events[] = $storedEvent;
    }

    public function addBulk(\ArrayObject $bulkEvents)
    {

    }

    public function setSerializer($serializer)
    {

    }
}
