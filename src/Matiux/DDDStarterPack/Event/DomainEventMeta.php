<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\Trace\CausationId;
use DDDStarterPack\Identity\Trace\CorrelationId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Tool\EnvVarUtil;

/**
 * @psalm-type SerializedMeta = array{
 *   event_id: string,
 *   event_version: int,
 *   context: null|string,
 *   domain_trace: array{
 *     correlation_id: string,
 *     causation_id: null|string
 *   }
 * }
 */
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

    /** @return SerializedMeta */
    public function serialize(): array
    {
        return [
            'event_id' => $this->eventId->value(),
            'event_version' => $this->version->v,
            'context' => $this->context,
            'domain_trace' => [
                'correlation_id' => $this->domainTrace->correlationId->value(),
                'causation_id' => $this->domainTrace->causationId?->value(),
            ],
        ];
    }

    /** @param SerializedMeta $data */
    public static function deserialize(array $data): self
    {
        return new self(
            EventId::from($data['event_id']),
            DomainTrace::fromIds(
                CorrelationId::from($data['domain_trace']['correlation_id']),
                null !== $data['domain_trace']['causation_id']
                    ? CausationId::from($data['domain_trace']['causation_id'])
                    : null,
            ),
            new DomainEventVersion((int) $data['event_version']),
            $data['context'],
        );
    }

    public function context(): string|null
    {
        return $this->context;
    }
}
