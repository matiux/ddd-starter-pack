<?php

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Domain\Event\DomainEvent;

abstract class CreateDomainEventFromConsumedMessage extends CreateFromConsumedMessage
{
    abstract protected function create($rawMessage): DomainEvent;
}
