<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

use DDDStarterPack\Command\Command;
use DDDStarterPack\Event\DomainEvent;

/**
 * @codeCoverageIgnore
 *
 * @template T
 */
abstract class CreateFromConsumedMessage
{
    /**
     * @param T                       $consumedMessage
     * @param null|\DateTimeImmutable $occurredAt
     *
     * @return Command|DomainEvent
     */
    public function execute($consumedMessage, null|\DateTimeImmutable $occurredAt = null)
    {
        $this->validateConsumedMessage($consumedMessage);

        return $this->create($consumedMessage);
    }

    /**
     * @param T $consumedMessage
     */
    abstract protected function validateConsumedMessage($consumedMessage): void;

    /**
     * @param T $consumedMessage
     *
     * @return Command|DomainEvent
     */
    abstract protected function create($consumedMessage): Command|DomainEvent;
}
