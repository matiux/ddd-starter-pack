<?php

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Domain\Aggregate\Event\DomainEvent;

abstract class CreateDomainEventFromRawFetchedMessage extends CreateFromRawFetchedMessage
{
    /**
     * @param $rawMessage
     * @return DomainEvent
     */
    abstract protected function create($rawMessage);
}
