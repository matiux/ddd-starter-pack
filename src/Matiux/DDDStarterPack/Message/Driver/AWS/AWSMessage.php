<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS;

use DDDStarterPack\Message\Message;

class AWSMessage implements Message
{
    public function __construct(
        private string $body,
        private \DateTimeImmutable|null $occurredAt,
        private string|null $type = null,
        private string|null $id = null,
        private array $extra = [],
    ) {}

    /**
     * @return null|string
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function exchangeName(): string|null
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
    public function id(): string|null
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
