<?php

namespace DddStarterPack\Domain\Model\Event;

interface EventStore
{
    public function add(BasicStoredDomainEvent $storedEvent);

    public function addBulk(BulkDomainEvent $bulkDomainEvent);

    public function allStoredEventsSince($anEventId);

    public function nextId(): int;
}
