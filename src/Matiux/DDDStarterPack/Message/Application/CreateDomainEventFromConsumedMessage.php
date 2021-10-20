<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Application;

use DDDStarterPack\Event\DomainEvent;

/**
 * @template T
 * @extends CreateFromConsumedMessage<T>
 */
abstract class CreateDomainEventFromConsumedMessage extends CreateFromConsumedMessage
{
    /**
     * @param T $rawMessage
     *
     * @return DomainEvent
     */
    abstract protected function create($rawMessage): DomainEvent;
}
