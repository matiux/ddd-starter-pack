<?php

namespace DDDStarterPack\Domain\Model\Event;

use DDDStarterPack\Domain\Event\Serializer;

interface EventStore
{
    public function add(DomainEvent $storedEvent);

    public function addBulk(\ArrayObject $bulkEvents);

    public function allStoredEventsSince(?int $anEventId): \ArrayObject;

    public function nextId(): ?int;

    public function setSerializer(Serializer $serializer);
}
