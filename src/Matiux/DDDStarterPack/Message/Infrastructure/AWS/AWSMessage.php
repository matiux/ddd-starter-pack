<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\AWS;

use BadMethodCallException;
use DateTimeImmutable;
use DDDStarterPack\Message\Application\Message;

class AWSMessage implements Message
{
    public function __construct(
        private string $body,
        private null|DateTimeImmutable $occurredAt,
        private null|string $type = null,
        private null|string $id = null,
        private array $extra = []
    ) {
    }

    /**
     * @return null|string
     * @codeCoverageIgnore
     */
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

    public function occurredAt(): null|DateTimeImmutable
    {
        return $this->occurredAt;
    }

    public function extra(): array
    {
        return $this->extra;
    }
}
