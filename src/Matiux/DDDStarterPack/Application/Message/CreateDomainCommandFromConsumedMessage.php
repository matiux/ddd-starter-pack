<?php

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Domain\Command\DomainCommand;

abstract class CreateDomainCommandFromConsumedMessage extends CreateFromConsumedMessage
{
    abstract protected function create($consumedMessage): DomainCommand;
}
