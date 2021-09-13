<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service;

/**
 * @template O
 */
interface TransactionalSession
{
    /**
     * @param callable $operation
     *
     * @return O
     */
    public function executeAtomically(callable $operation);
}
