<?php

namespace Tests\DddStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory;

use DddStarterPack\Domain\Model\Event\EventStore;
use DddStarterPack\Domain\Model\Event\StoredDomainEvent;

class InMemoryEventStore implements EventStore
{
    private $events = [];

    public function append(StoredDomainEvent $storedEvent)
    {
        $this->events[] = $storedEvent;
    }

    public function allStoredEventsSince($anEventId)
    {
        $events = array_filter($this->events, function (StoredDomainEvent $storedEvent) use ($anEventId) {

            return $storedEvent->eventId() > $anEventId;
        });

        return $events;
    }

    public function nextId(): int
    {
        $greatesId = 0;

        array_walk($this->events, function (StoredDomainEvent $storedEvent) use (&$greatesId) {

            $greatesId = $storedEvent->eventId() > $greatesId ? $storedEvent->eventId() : $greatesId;

        });

        return $greatesId + 1;
    }
}
