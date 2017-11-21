<?php

namespace DddStarterPack\Domain\Model\Event;

interface StoredEventFactory
{
    public function build(string $eventType, \DateTimeImmutable $occuredOn, string $serializedEvent): StoredEvent;
}
