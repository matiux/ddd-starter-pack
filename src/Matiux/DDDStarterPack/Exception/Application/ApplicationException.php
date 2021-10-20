<?php

declare(strict_types=1);

namespace DDDStarterPack\Exception\Application;

use DDDStarterPack\Exception\Domain\DomainException;

abstract class ApplicationException extends DomainException
{
}
