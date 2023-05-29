<?php

declare(strict_types=1);

namespace DDDStarterPack\Command;

use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Type\DateTimeRFC;

abstract readonly class Command
{
    public string $commandName;

    public function __construct(
        public CommandId $commandId,
        public AggregateId $aggregateId,
        public DomainTrace $domainTrace,
        public DateTimeRFC $occurredAt,
    ) {
        $this->commandName = strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0',
                (new \ReflectionClass($this))->getShortName(),
            ),
        );
    }
}
