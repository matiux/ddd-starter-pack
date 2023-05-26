<?php

declare(strict_types=1);

namespace DDDStarterPack\Service;

use DDDStarterPack\Command\DomainCommand;

/**
 * @template I of DomainCommand
 *
 * @extends Service<I, void>
 */
interface CommandService extends Service
{
    /**
     * @param I $command
     *
     * @psalm-assert DomainCommand $command
     */
    public function execute($command): void;
}
