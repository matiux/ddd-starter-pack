<?php

namespace DddStarterPack\Application\EventSystem\Event;

use DddStarterPack\Domain\Model\Event\StoredEvent;

interface EventStore
{
    public function append(StoredEvent $storedEvent);

    public function allStoredEventsSince($anEventId);
}
