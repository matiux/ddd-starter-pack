<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Application;

use DDDStarterPack\Command\DomainCommand;

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
