<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service;

/**
 * @template T of \DDDStarterPack\Domain\Command\DomainCommand
 * @implements ApplicationService<T>
 */
abstract class CommandService implements ApplicationService
{
    /**
     * @param T $command
     * @psalm-assert \DDDStarterPack\Domain\Command\DomainCommand $command
     */
    abstract public function execute($command): void;
}
