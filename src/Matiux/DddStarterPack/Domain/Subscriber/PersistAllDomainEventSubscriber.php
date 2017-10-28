<?php

namespace DddStarterPack\Domain\Subscriber;

use DddStarterPack\Domain\Event\DomainEvent;
use DddStarterPack\Domain\Event\DomainEventSubscriber;
use DddStarterPack\Domain\Event\EventStore;

class PersistAllDomainEventSubscriber implements DomainEventSubscriber
{
    private $eventStore;

    public function __construct(EventStore $anEventStore)
    {
        $this->eventStore = $anEventStore;
    }

    public function handle(DomainEvent $aDomainEvent)
    {
        $this->eventStore->append($aDomainEvent);
    }

    public function isSubscribedTo(DomainEvent $aDomainEvent): bool
    {
        return true;
    }
}
