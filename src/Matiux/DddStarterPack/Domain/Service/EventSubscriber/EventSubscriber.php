<?php

namespace DddStarterPack\Domain\Service\EventSubscriber;

use DddStarterPack\Domain\Model\Event\DomainEvent;

interface EventSubscriber
{
    public function handle(DomainEvent $anEvent);

    public function isSubscribedTo(DomainEvent $aDomainEvent): bool;
}
