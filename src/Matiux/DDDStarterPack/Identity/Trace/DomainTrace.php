<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity\Trace;

use DDDStarterPack\Command\CommandId;
use DDDStarterPack\Event\EventId;

final readonly class DomainTrace
{
    private function __construct(
        public CorrelationId $correlationId,
        public CausationId|null $causationId = null,
    ) {}

    public static function init(CommandId|EventId $id): self
    {
        return self::fromIds(CorrelationId::from($id->value()), null);
    }

    public static function continueFrom(DomainTrace $domainTraceForCorrelation, CommandId|EventId $causation): self
    {
        return new self($domainTraceForCorrelation->correlationId, CausationId::from($causation->value()));
    }

    public static function fromIds(CorrelationId $correlationId, CausationId|null $causationId): self
    {
        return new self($correlationId, $causationId);
    }
}
