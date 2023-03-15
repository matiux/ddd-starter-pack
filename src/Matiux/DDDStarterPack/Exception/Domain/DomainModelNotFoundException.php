<?php

declare(strict_types=1);

namespace DDDStarterPack\Exception\Domain;

/**
 * @codeCoverageIgnore
 */
abstract class DomainModelNotFoundException extends DomainException
{
    public const MESSAGE = 'Aggregate not found';
}
