<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Service;

interface TransactionalSession
{
    /**
     * @param callable $operation
     *
     * @return mixed
     */
    public function executeAtomically(callable $operation);
}
