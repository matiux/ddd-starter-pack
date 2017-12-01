<?php

namespace DddStarterPack\Domain\Event\Subscriber;

use DddStarterPack\Domain\Model\Event\DomainEvent;

interface EventSubscriber
{
    public function handle(DomainEvent $anEvent);

    public function isSubscribedTo(DomainEvent $aDomainEvent): bool;
}
