<?php

namespace DDDStarterPack\Domain\Exception;

use Throwable;

abstract class DomainModelNotFoundException extends DomainException
{
    const MESSAGE = 'Aggregate not found';

    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
