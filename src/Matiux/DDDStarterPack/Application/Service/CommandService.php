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
     * @param T $request
     * @psalm-assert \DDDStarterPack\Domain\Command\DomainCommand $request
     */
    abstract public function execute($request): void;
}
