<?php

namespace DddStarterPack\Application\Service;

interface TransactionalSession
{
    public function executeAtomically(callable $operation);
}
