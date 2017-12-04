<?php

namespace DDDStarterPack\Application\Service;

interface TransactionalSession
{
    public function executeAtomically(callable $operation);
}
