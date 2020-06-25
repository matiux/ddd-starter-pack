<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Domain\Event\DomainEvent;

/**
 * Class CreateDomainEventFromConsumedMessage.
 *
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
