<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\InMemory;

use BadMethodCallException;
use DateTimeImmutable;
use DDDStarterPack\Message\Infrastructure\Message;

class InMemoryMessage implements Message
{
    public function __construct(
        private string $body,
        private null|DateTimeImmutable $occurredAt,
        private null|string $type = null,
        private null|string $id = null,
    ) {
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function exchangeName(): string
    {
        throw new BadMethodCallException();
    }

    public function body(): string
    {
        return $this->body;
    }

    public function type(): null|string
    {
        return $this->type;
    }

    public function id(): mixed
    {
        return $this->id;
    }

    public function occurredAt(): DateTimeImmutable|null
    {
        return $this->occurredAt;
    }
}
