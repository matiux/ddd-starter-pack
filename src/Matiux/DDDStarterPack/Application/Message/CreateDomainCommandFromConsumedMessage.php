<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Domain\Command\DomainCommand;

/**
 * @template T
 * @extends CreateFromConsumedMessage<T>
 */
abstract class CreateDomainCommandFromConsumedMessage extends CreateFromConsumedMessage
{
    /**
     * @param T $consumedMessage
     *
     * @return DomainCommand
     */
    abstract protected function create($consumedMessage): DomainCommand;
}
