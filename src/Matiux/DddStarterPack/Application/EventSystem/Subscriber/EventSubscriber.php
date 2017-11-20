<?php

namespace DddStarterPack\Application\EventSystem\Subscriber;

use DddStarterPack\Application\EventSystem\Event\Event;

interface EventSubscriber
{
    public function handle(Event $anEvent);

    public function isSubscribedTo(Event $aDomainEvent): bool;
}
