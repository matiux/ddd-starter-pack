<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\InMemory;

use DDDStarterPack\Message\Message;

class InMemoryMessage implements Message
{
    public function __construct(
        private string $body,
        private \DateTimeImmutable|null $occurredAt,
        private string|null $type = null,
        private string|null $id = null,
        private array $extra = [],
    ) {}

    /**
     * @return string
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function exchangeName(): string
    {
        throw new \BadMethodCallException();
    }

    #[\Override]
    public function body(): string
    {
        return $this->body;
    }

    #[\Override]
    public function type(): string|null
    {
        return $this->type;
    }

    #[\Override]
    public function id(): mixed
    {
        return $this->id;
    }

    #[\Override]
    public function occurredAt(): \DateTimeImmutable|null
    {
        return $this->occurredAt;
    }

    #[\Override]
    public function extra(): array
    {
        return $this->extra;
    }
}
