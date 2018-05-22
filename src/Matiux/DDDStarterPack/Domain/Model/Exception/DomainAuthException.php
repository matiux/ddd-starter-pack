<?php

namespace DDDStarterPack\Domain\Model\Exception;

use Throwable;

abstract class DomainAuthException extends DomainException
{
    const MESSAGE = 'Model not found';

    public function __construct($message = "", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
