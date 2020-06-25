<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Exception;

use Throwable;

abstract class DomainModelNotFoundException extends DomainException
{
    const MESSAGE = 'Aggregate not found';

    public function __construct(string $message = '', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
