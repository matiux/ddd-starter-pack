<?php


namespace DddStarterPack\Domain\Event;


interface DomainEventSubscriber
{
    public function handle(DomainEvent $aDomainEvent);

    public function isSubscribedTo(DomainEvent $aDomainEvent): bool;
}
