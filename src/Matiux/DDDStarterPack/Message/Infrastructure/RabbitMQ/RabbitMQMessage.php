<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\RabbitMQ;

use DateTimeImmutable;
use DDDStarterPack\Message\Application\Message;

class RabbitMQMessage implements Message
{
    private $body;
    private $exchangeName;
    private $occurredAt;
    private $type;
    private $id;

    public function __construct(string $body, string $exchangeName = null, DateTimeImmutable $occurredAt = null, string $type = null, $id = null)
    {
        $this->body = $body;
        $this->exchangeName = $exchangeName;
        $this->occurredAt = $occurredAt;
        $this->type = $type;
        $this->id = $id;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function exchangeName(): ?string
    {
        return $this->exchangeName;
    }

    public function occurredAt(): ?DateTimeImmutable
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
