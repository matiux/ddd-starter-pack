<?php

declare(strict_types=1);

namespace DDDStarterPack\Exception;

/**
 * @codeCoverageIgnore
 */
abstract class DomainModelNotFoundException extends DomainException
{
    public const MESSAGE = 'Aggregate not found';
}
