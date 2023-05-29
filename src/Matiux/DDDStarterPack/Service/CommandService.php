<?php

declare(strict_types=1);

namespace DDDStarterPack\Service;

use DDDStarterPack\Command\Command;

/**
 * @template I of Command
 *
 * @extends Service<I, void>
 */
interface CommandService extends Service
{
    /**
     * @param I $command
     *
     * @psalm-assert Command $command
     */
    public function execute($command): void;
}
