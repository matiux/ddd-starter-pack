<?php

namespace DddStarterPack\Domain\Model\Event;

interface EventStore
{
    public function add(StoredDomainEvent $storedEvent);

    public function addBulk(BulkDomainEvent $bulkDomainEvent);

    public function allStoredEventsSince($anEventId);

    public function nextId(): int;
}
