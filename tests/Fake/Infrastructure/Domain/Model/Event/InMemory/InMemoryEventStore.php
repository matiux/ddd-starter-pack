<?php

namespace Tests\DddStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory;

use DddStarterPack\Domain\Model\Event\EventStore;
use DddStarterPack\Domain\Model\Event\StoredEvent;

class InMemoryEventStore implements EventStore
{
    private $events = [];

    public function append(StoredEvent $storedEvent)
    {
        $this->events[] = $storedEvent;
    }

    public function allStoredEventsSince($anEventId)
    {
        $events = array_filter($this->events, function (StoredEvent $storedEvent) use ($anEventId) {

            return $storedEvent->eventId() > $anEventId;
        });

        return $events;
    }

    public function nextId(): int
    {
        $greatesId = 0;

        array_walk($this->events, function (StoredEvent $storedEvent) use (&$greatesId) {

            $greatesId = $storedEvent->eventId() > $greatesId ? $storedEvent->eventId() : $greatesId;

        });

        return $greatesId + 1;
    }
}
