<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Exception;

use DDDStarterPack\Domain\Exception\DomainException;

abstract class ApplicationException extends DomainException
{
}
