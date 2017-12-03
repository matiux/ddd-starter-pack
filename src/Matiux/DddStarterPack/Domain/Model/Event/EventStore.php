<?php

namespace DddStarterPack\Domain\Model\Event;

interface EventStore
{
    public function add(BasicDomainEvent $storedEvent);

    public function addBulk(\ArrayObject $bulkEvents);

    public function allStoredEventsSince($anEventId): \ArrayObject;

    public function nextId(): int;
}
