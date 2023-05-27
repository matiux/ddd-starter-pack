<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

use DDDStarterPack\Command\Command;

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
     * @return Command
     */
    abstract protected function create($consumedMessage): Command;
}
