<?php

namespace DddStarterPack\Domain\Model\Event;

interface EventStore
{
    public function append(StoredDomainEvent $storedEvent);

    public function allStoredEventsSince($anEventId);

    public function nextId(): int;
}
