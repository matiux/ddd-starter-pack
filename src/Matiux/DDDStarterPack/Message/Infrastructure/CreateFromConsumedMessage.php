<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure;

use DateTimeImmutable;
use DDDStarterPack\Command\DomainCommand;
use DDDStarterPack\Event\DomainEvent;

/**
 * @codeCoverageIgnore
 * @template T
 */
abstract class CreateFromConsumedMessage
{
    /**
     * @param T                      $consumedMessage
     * @param null|DateTimeImmutable $occurredAt
     *
     * @return DomainCommand|DomainEvent
     */
    public function execute($consumedMessage, DateTimeImmutable $occurredAt = null)
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
     * @return DomainCommand|DomainEvent
     */
    abstract protected function create($consumedMessage): DomainCommand|DomainEvent;
}
