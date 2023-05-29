<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

final readonly class DomainEventVersion
{
    public function __construct(public int $v)
    {
        if (1 > $v) {
            throw new \LogicException();
        }
    }
}
