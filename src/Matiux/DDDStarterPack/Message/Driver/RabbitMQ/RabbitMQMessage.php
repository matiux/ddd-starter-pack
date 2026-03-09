<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\RabbitMQ;

use DDDStarterPack\Message\Message;

class RabbitMQMessage implements Message
{
    public function __construct(
        private string $body,
        private string|null $exchangeName = null,
        private \DateTimeImmutable|null $occurredAt = null,
        private string|null $type = null,
        private $id = null,
        private array $extra = [],
    ) {}

    public function body(): string
    {
        return $this->body;
    }

    public function exchangeName(): string|null
    {
        return $this->exchangeName;
    }

    public function occurredAt(): \DateTimeImmutable|null
    {
        return $this->occurredAt;
    }

    public function type(): string|null
    {
        return $this->type;
    }

    public function id(): mixed
    {
        return $this->id;
    }

    public function extra(): array
    {
        return $this->extra;
    }
}
