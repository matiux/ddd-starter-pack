<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Identity\Uuid;
use DDDStarterPack\Type\DateTimeRFC;

/**
 * @template I of Uuid
 */
abstract class IdentifiableDomainEvent extends DomainEvent
{
    public const AGGREGATE_ID_KEY = 'id';

    /**
     * @param I           $aggregateId
     * @param DateTimeRFC $occurredAt
     */
    public function __construct(
        private mixed $aggregateId,
        DateTimeRFC $occurredAt,
    ) {
        parent::__construct($occurredAt);
    }

    /**
     * @return string[]
     */
    protected function basicSerialize(): array
    {
        return [
            self::AGGREGATE_ID_KEY => $this->aggregateId->value(),
        ] + parent::basicSerialize();
    }

    /**
     * @return I
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }
}
