<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\RabbitMQ;

use DDDStarterPack\Message\Message;

class RabbitMQMessage implements Message
{
    public function __construct(
        private string $body,
        private null|string $exchangeName = null,
        private null|\DateTimeImmutable $occurredAt = null,
        private null|string $type = null,
        private $id = null,
        private array $extra = [],
    ) {}

    public function body(): string
    {
        return $this->body;
    }

    public function exchangeName(): null|string
    {
        return $this->exchangeName;
    }

    public function occurredAt(): null|\DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function type(): null|string
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
