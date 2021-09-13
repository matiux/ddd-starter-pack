<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Service;

/**
 * @template I of \DDDStarterPack\Domain\Command\DomainCommand
 *
 * @extends Service<I, void>
 */
interface CommandService extends Service
{
    /**
     * @param I $command
     * @psalm-assert \DDDStarterPack\Domain\Command\DomainCommand $command
     */
    public function execute($command): void;
}
