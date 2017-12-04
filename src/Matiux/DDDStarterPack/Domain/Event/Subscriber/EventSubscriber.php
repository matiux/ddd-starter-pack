<?php

namespace DDDStarterPack\Domain\Event\Subscriber;

use DDDStarterPack\Domain\Model\Event\DomainEvent;

interface EventSubscriber
{
    public function handle(DomainEvent $anEvent);

    public function isSubscribedTo(DomainEvent $aDomainEvent): bool;
}
