<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\Trace\DomainTrace;

final readonly class DomainEventMeta
{
    public function __construct(
        public EventId $eventId,
        public DomainTrace $domainTrace,
        public DomainEventVersion $version,
    ) {
    }
}
