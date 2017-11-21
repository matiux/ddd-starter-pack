<?php

namespace DddStarterPack\Domain\Model\Event;

interface EventStore
{
    public function append(StoredEvent $storedEvent);

    public function allStoredEventsSince($anEventId);

    public function nextId(): int;
}
