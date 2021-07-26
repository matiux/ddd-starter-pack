<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use BadMethodCallException;
use DateTimeImmutable;
use DDDStarterPack\Application\Message\Message;

class SQSMessage implements Message
{
    private $type;
    private $body;
    private $id;
    private $occurredAt;
    private $extra;

    public function __construct(string $body, DateTimeImmutable $occurredAt, string $type = null, string $id = null, array $extra = [])
    {
        $this->body = $body;
        $this->occurredAt = $occurredAt;
        $this->type = $type;
        $this->id = $id;
        $this->extra = $extra;
    }

    public function exchangeName(): ?string
    {
        throw new BadMethodCallException();
    }

    public function body(): string
    {
        return $this->body;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function extra(): array
    {
        return $this->extra;
    }
}
