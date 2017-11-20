<?php

namespace DddStarterPack\Domain\Subscriber;

use DddStarterPack\Application\EventSystem\Event\Event;
use DddStarterPack\Application\EventSystem\Event\EventStore;
use DddStarterPack\Application\EventSystem\Subscriber\EventSubscriber;
use DddStarterPack\Application\Serializer\Serializer;
use DddStarterPack\Domain\Model\Event\StoredEvent;

class PersistAllEventSubscriber implements EventSubscriber
{
    private $eventStore;
    private $serializer;

    public function __construct(EventStore $anEventStore, Serializer $serializer)
    {
        $this->eventStore = $anEventStore;
        $this->serializer = $serializer;
    }

    public function handle(Event $anEvent)
    {
        $serializedEvents = $this->serializer->serialize($anEvent, 'json');

        $storedEvent = new StoredEvent(
            get_class($anEvent),
            $anEvent->occurredOn(),
            $serializedEvents
        );

        $this->eventStore->append($storedEvent);
    }

    public function isSubscribedTo(Event $aDomainEvent): bool
    {
        return true;
    }
}
