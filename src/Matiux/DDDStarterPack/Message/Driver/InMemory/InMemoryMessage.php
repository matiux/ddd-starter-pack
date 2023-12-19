<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\InMemory;

use DDDStarterPack\Message\Message;

class InMemoryMessage implements Message
{
    public function __construct(
        private string $body,
        private null|\DateTimeImmutable $occurredAt,
        private null|string $type = null,
        private null|string $id = null,
        private array $extra = [],
    ) {}

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function exchangeName(): string
    {
        throw new \BadMethodCallException();
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

    public function occurredAt(): null|\DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function extra(): array
    {
        return $this->extra;
    }
}
