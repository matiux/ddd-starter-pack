<?php

namespace DDDStarterPack\Infrastructure\Application\Message\RabbitMQ;

use DateTimeInterface;
use DDDStarterPack\Application\Message\Message;

class RabbitMQMessage implements Message
{
    private $exchangeName;
    private $id;
    private $body;
    private $type;
    private $occurredAt;

    public function __construct(string $exchangeName, int $id, string $body, string $type, DateTimeInterface $occurredAt)
    {
        $this->exchangeName = $exchangeName;
        $this->id = $id;
        $this->body = $body;
        $this->type = $type;
        $this->occurredAt = $occurredAt;
    }

    public function exchangeName(): string
    {
        return $this->exchangeName;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function id()
    {
        return $this->id;
    }

    public function occurredAt(): DateTimeInterface
    {
        return $this->occurredAt;
    }
}
