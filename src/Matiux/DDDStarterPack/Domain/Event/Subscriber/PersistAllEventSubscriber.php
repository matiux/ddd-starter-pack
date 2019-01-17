<?php

namespace DDDStarterPack\Domain\Event\Subscriber;

use DDDStarterPack\Domain\Model\Event\DomainEvent;
use DDDStarterPack\Domain\Model\Event\EventStore;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventFactory;
use DDDStarterPack\Domain\Service\Serializer;

class PersistAllEventSubscriber implements EventSubscriber
{
    private $eventStore;
    private $serializer;
    private $storedEventFactory;

    public function __construct(EventStore $anEventStore, Serializer $serializer, StoredDomainEventFactory $storedEventFactory)
    {
        $this->eventStore = $anEventStore;
        $this->serializer = $serializer;
        $this->storedEventFactory = $storedEventFactory;
    }

    public function handle(DomainEvent $anEvent)
    {
        $serializedEvents = $this->serializer->serialize($anEvent, 'json');

        $storedEvent = $this->storedEventFactory->build(
            get_class($anEvent),
            $anEvent->occurredOn(),
            $serializedEvents
        );

        $this->eventStore->add($storedEvent);
    }

    public function isSubscribedTo(DomainEvent $aDomainEvent): bool
    {
        return true;
    }
}
