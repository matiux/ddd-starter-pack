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
        private null|string $messageId = null,
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function sentMessages(): int
    {
        return $this->sentMessages;
    }

    public function originalResponse(): mixed
    {
        return $this->originalResponse;
    }

    public function body(): mixed
    {
        if ($this->success) {
            return ['success' => true];
        }

        return ['success' => false];
    }

    public function sentMessageId(): string
    {
        return $this->messageId ?: (string) Uuid::uuid4();
    }
}
