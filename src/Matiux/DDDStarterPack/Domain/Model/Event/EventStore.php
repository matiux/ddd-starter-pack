<?php

namespace DDDStarterPack\Domain\Model\Event;

interface EventStore
{
    public function add(DomainEvent $domainEvent);

    public function addBulk(\ArrayObject $bulkEvents);

    public function allStoredEventsSince(?int $anEventId, ?int $limit = null): \ArrayObject;

    public function nextId(): ?int;

    public function setSerializer($serializer);

    public function setStoredDomainEventFactory(StoredDomainEventFactory $storedDomainEventFactory);
}
