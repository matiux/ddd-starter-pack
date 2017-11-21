<?php

namespace DddStarterPack\Application\Event\Subscriber;

use DddStarterPack\Domain\Model\Event\Event;

interface EventSubscriber
{
    public function handle(Event $anEvent);

    public function isSubscribedTo(Event $aDomainEvent): bool;
}
