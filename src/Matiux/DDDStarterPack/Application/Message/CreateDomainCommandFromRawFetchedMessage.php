<?php

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Domain\Command\DomainCommand;

abstract class CreateDomainCommandFromRawFetchedMessage extends CreateFromRawFetchedMessage
{
    /**
     * @param $rawMessage
     * @return DomainCommand
     */
    abstract protected function create($rawMessage);
}
