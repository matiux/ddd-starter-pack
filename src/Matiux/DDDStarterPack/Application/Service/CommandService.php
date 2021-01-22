<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service;

/**
 * @template I of \DDDStarterPack\Domain\Command\DomainCommand
 *
 * @implements ApplicationService<I, void>
 */
abstract class CommandService implements ApplicationService
{
    /**
     * @param I $command
     * @psalm-assert \DDDStarterPack\Domain\Command\DomainCommand $command
     */
    abstract public function execute($command): void;
}
