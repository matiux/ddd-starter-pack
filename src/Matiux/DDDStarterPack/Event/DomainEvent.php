<?php

declare(strict_types=1);

namespace DDDStarterPack\Event;

use DDDStarterPack\Type\DateTimeRFC;
use Exception;

abstract class DomainEvent
{
    /**
     * @param DateTimeRFC $occurredAt
     */
    public function __construct(
        private DateTimeRFC $occurredAt,
    ) {
    }

    /**
     * @return string[]
     */
    protected function basicSerialize(): array
    {
        return [
            'occurred_at' => $this->occurredAt->value(),
        ];
    }

    public function occurredAt(): DateTimeRFC
    {
        return $this->occurredAt;
    }

    /**
     * @param string $occurredAt
     *
     * @throws Exception
     *
     * @return DateTimeRFC
     */
    protected static function createOccurredAt(string $occurredAt): DateTimeRFC
    {
        return DateTimeRFC::createFrom($occurredAt);
    }
}
