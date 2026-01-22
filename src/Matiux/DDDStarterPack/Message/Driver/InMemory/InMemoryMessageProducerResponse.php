<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\InMemory;

use DDDStarterPack\Message\MessageProducerResponse;
use Ramsey\Uuid\Uuid;

class InMemoryMessageProducerResponse implements MessageProducerResponse
{
    public function __construct(
        private int $sentMessages,
        private bool $success,
        private mixed $originalResponse,
        private string|null $messageId = null,
    ) {}

    #[\Override]
    public function isSuccess(): bool
    {
        return $this->success;
    }

    #[\Override]
    public function sentMessages(): int
    {
        return $this->sentMessages;
    }

    #[\Override]
    public function originalResponse(): mixed
    {
        return $this->originalResponse;
    }

    #[\Override]
    public function body(): mixed
    {
        if ($this->success) {
            return ['success' => true];
        }

        return ['success' => false];
    }

    #[\Override]
    public function sentMessageId(): string
    {
        return \is_string($this->messageId) ? $this->messageId : (string) Uuid::uuid4();
    }
}
