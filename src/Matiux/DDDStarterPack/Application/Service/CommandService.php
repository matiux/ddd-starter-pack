<?php

namespace DDDStarterPack\Application\Service;

use DDDStarterPack\Domain\Command\DomainCommand;

abstract class CommandService implements ApplicationService
{
    public function execute($request = null): void
    {
        $this->doExecute($request);
    }

    abstract protected function doExecute(DomainCommand $command): void;
}
