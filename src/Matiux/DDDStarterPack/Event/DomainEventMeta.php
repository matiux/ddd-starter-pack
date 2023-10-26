<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Tool\EnvVarUtil;

final class DomainEventMeta
{
    public function __construct(
        readonly public EventId $eventId,
        readonly public DomainTrace $domainTrace,
        readonly public DomainEventVersion $version,
        private null|string $context = null,
    ) {
        $this->context ??= EnvVarUtil::getOrNull('SERVICE_NAME');
    }

    public function toArray(): array
    {
        return [
            'event_id' => $this->eventId->value(),
            'correlation_id' => $this->domainTrace->correlationId->value(),
            'causation_id' => $this->domainTrace->causationId->value(),
            'event_version' => $this->version->v,
            'context' => $this->context,
        ];
    }

    public function context(): null|string
    {
        return $this->context;
    }
}
