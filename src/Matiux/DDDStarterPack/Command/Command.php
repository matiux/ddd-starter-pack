<?php

declare(strict_types=1);

namespace DDDStarterPack\Command;

use DDDStarterPack\Identity\AggregateId;
use DDDStarterPack\Identity\Trace\DomainTrace;
use DDDStarterPack\Type\DateTimeRFC;

/**
 * @template I of AggregateId
 */
abstract readonly class Command
{
    public string $commandName;

    /**
     * @param I $aggregateId
     */
    public function __construct(
        public CommandId $commandId,
        public mixed $aggregateId,
        public DomainTrace $domainTrace,
        public DateTimeRFC $operationDate,
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
