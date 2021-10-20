<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Application;

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
