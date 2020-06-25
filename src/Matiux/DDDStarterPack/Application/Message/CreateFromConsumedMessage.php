<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use DateTimeImmutable;
use DDDStarterPack\Domain\Command\DomainCommand;
use DDDStarterPack\Domain\Event\DomainEvent;

/**
 * Class CreateFromConsumedMessage.
 *
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
    abstract protected function create($consumedMessage);
}
