<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

use DDDStarterPack\Command\DomainCommand;

/**
 * @codeCoverageIgnore
 *
 * @template T
 *
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
