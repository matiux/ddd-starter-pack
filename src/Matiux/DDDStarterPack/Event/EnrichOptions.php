<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\Trace\DomainTrace;

readonly class EnrichOptions
{
    public function __construct(
        public DomainTrace $domainTrace,
        public null|EventId $eventId = null,
        public null|string $context = null,
    ) {}
}
