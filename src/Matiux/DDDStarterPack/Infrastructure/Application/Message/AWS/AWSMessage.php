<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS;

use BadMethodCallException;
use DateTimeImmutable;
use DDDStarterPack\Application\Message\Message;

class AWSMessage implements Message
{
    public function __construct(
        private string $body,
        private DateTimeImmutable $occurredAt,
        private null | string $type = null,
        private null | string $id = null,
        private array $extra = []
    ) {
    }

    public function exchangeName(): null|string
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

    public function id(): null|string
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
