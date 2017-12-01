<?php

namespace DddStarterPack\Domain\Service\EventSubscriber;

use DddStarterPack\Domain\Model\Event\Event;

interface EventSubscriber
{
    public function handle(Event $anEvent);

    public function isSubscribedTo(Event $aDomainEvent): bool;
}
