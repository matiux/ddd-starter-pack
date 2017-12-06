<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\DomainEvent;
use DDDStarterPack\Domain\Model\Event\EventStore;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventFactory;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventInterface;
use Tests\DDDStarterPack\Infrastructure\Domain\Event\FakeEventSerializer;

class InMemoryEventStore implements EventStore
{
    private $events = [];

    /**
     * @var StoredDomainEventFactory
     */
    private $storedDomainEventFactory;

    public function allStoredEventsSince(?int $anEventId, ?int $limit = null): \ArrayObject
    {
        $events = array_filter($this->events, function (StoredDomainEventInterface $storedEvent) use ($anEventId) {

            return $storedEvent->eventId() > $anEventId;
        });

        return new \ArrayObject($events);
    }

    public function nextId(): int
    {
        $greatesId = 0;

        array_walk($this->events, function (StoredDomainEventInterface $storedEvent) use (&$greatesId) {

            $greatesId = $storedEvent->eventId() > $greatesId ? $storedEvent->eventId() : $greatesId;

        });

        return $greatesId + 1;
    }

    public function add(DomainEvent $domainEvent)
    {
        $storedEvent = $this->storedDomainEventFactory->build(
            $this->nextId(),
            get_class($domainEvent),
            $domainEvent->occurredOn(),
            (new FakeEventSerializer())->serialize($domainEvent, 'json')
        );

        $this->events[] = $storedEvent;
    }

    public function addBulk(\ArrayObject $bulkEvents)
    {

    }

    public function setSerializer($serializer)
    {

    }

    public function setStoredDomainEventFactory(StoredDomainEventFactory $storedDomainEventFactory)
    {
        $this->storedDomainEventFactory = $storedDomainEventFactory;
    }
}
