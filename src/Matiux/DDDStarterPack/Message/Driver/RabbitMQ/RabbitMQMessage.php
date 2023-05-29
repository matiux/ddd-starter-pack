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
    ) {
    }

    public function body(): string
    {
        return $this->body;
    }

    public function exchangeName(): ?string
    {
        return $this->exchangeName;
    }

    public function occurredAt(): ?\DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function id(): mixed
    {
        return $this->id;
    }
}
