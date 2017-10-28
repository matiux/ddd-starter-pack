<?php

namespace DddStarterPack\Domain\Event;

interface EventStore
{
    public function append(DomainEvent $aDomainEvent);

    public function allStoredEventsSince($anEventId);
}
