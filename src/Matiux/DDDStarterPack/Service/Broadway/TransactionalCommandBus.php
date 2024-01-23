<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Broadway;

use Broadway\CommandHandling\CommandBus;
use Broadway\CommandHandling\CommandHandler;
use DDDStarterPack\Exception\TransactionFailedException;
use DDDStarterPack\Service\TransactionalSession;

class TransactionalCommandBus implements CommandBus
{
    public function __construct(
        protected CommandBus $commandBus,
        protected TransactionalSession $session,
    ) {}

    /** @throws TransactionFailedException */
    public function dispatch($command): void
    {
        $this->session->executeAtomically(fn () => $this->commandBus->dispatch($command));
    }

    public function subscribe(CommandHandler $handler): void
    {
        $this->commandBus->subscribe($handler);
    }
}
