<?php

declare(strict_types=1);

namespace DDDStarterPack\Identity\Trace;

use DDDStarterPack\Command\CommandId;
use DDDStarterPack\Event\EventId;

final readonly class DomainTrace
{
    public function __construct(
        public CorrelationId $correlationId,
        public CausationId $causationId,
    ) {
    }

    public static function init(EventId|CommandId $id): self
    {
        return new self(CorrelationId::from($id->value()), CausationId::from($id->value()));
    }

    public static function createFrom(DomainTrace $domainTrace, EventId|CommandId $id): self
    {
        return new self($domainTrace->correlationId, CausationId::from($id->value()));
    }
}
