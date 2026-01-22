<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Tool\EnvVarUtil;

final class DomainEventMeta
{
    public function __construct(
        public readonly EventId $eventId,
        public readonly DomainTrace $domainTrace,
        public readonly DomainEventVersion $version,
        private string|null $context = null,
    ) {
        $this->context ??= EnvVarUtil::getOrNull('SERVICE_NAME');
    }

    public function toArray(bool $camelCase = false): array
    {
        $camelCaseKeys = ['eventId', 'correlationId', 'causationId', 'eventVersion', 'context'];
        $snakeCaseKeys = ['event_id', 'correlation_id', 'causation_id', 'event_version', 'context'];

        $values = [
            $this->eventId->value(),
            $this->domainTrace->correlationId->value(),
            $this->domainTrace->causationId->value(),
            $this->version->v,
            $this->context,
        ];

        $keys = $camelCase ? $camelCaseKeys : $snakeCaseKeys;

        return array_combine($keys, $values);
    }

    public function context(): string|null
    {
        return $this->context;
    }
}
