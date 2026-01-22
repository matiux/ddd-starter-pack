<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS;

use DDDStarterPack\Message\Message;
use Override;

class AWSMessage implements Message
{
    public function __construct(
        private string $body,
        private null|\DateTimeImmutable $occurredAt,
        private null|string $type = null,
        private null|string $id = null,
        private array $extra = [],
    ) {}

    /**
     * @return null|string
     *
     * @codeCoverageIgnore
     */
    #[Override]
    public function exchangeName(): null|string
    {
        throw new \BadMethodCallException();
    }

    #[Override]
    public function body(): string
    {
        return $this->body;
    }

    #[Override]
    public function type(): null|string
    {
        return $this->type;
    }

    #[Override]
    public function id(): null|string
    {
        return $this->id;
    }

    #[Override]
    public function occurredAt(): null|\DateTimeImmutable
    {
        return $this->occurredAt;
    }

    #[Override]
    public function extra(): array
    {
        return $this->extra;
    }
}
