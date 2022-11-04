<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Domain;

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

    /**
     * @param I $command
     *
     * @psalm-assert DomainCommand $command
     */
    public function __invoke($command): void;
}
