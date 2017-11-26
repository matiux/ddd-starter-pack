<?php

namespace DddStarterPack\Domain\Model\Exception;

use Throwable;

abstract class DomainModelAuthException extends DomainModelException
{
    const message = 'Model not found';

    public function __construct($message = "", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
