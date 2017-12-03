<?php

namespace DddStarterPack\Domain\Model\Event;

use DddStarterPack\Domain\Event\Subscriber\Serializer;

interface EventStore
{
    public function add(DomainEvent $storedEvent);

    public function addBulk(\ArrayObject $bulkEvents);

    public function allStoredEventsSince($anEventId): \ArrayObject;

    public function nextId(): int;

    public function setSerializer(Serializer);
}
