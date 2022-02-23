<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure;

use DDDStarterPack\Event\DomainEvent;

/**
 * @codeCoverageIgnore
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
