<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Domain;

use DDDStarterPack\Exception\TransactionFailedException;

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
