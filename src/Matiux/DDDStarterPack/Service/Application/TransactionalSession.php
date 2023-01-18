<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Application;

use DDDStarterPack\Exception\Application\TransactionFailedException;

/**
 * @template O
 */
interface TransactionalSession
{
    /**
     * @param callable $operation
     *
     * @throws TransactionFailedException
     *
     * @return O
     */
    public function executeAtomically(callable $operation);
}
