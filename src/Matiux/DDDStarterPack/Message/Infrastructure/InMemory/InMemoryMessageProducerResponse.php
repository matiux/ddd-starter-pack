<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\InMemory;

use DDDStarterPack\Message\Application\MessageProducerResponse;
use Ramsey\Uuid\Uuid;

class InMemoryMessageProducerResponse implements MessageProducerResponse
{
    private $sentMessages;
    private $success;

    /** @var mixed */
    private $originalResponse;
    private $messageId;

    /**
     * InMemoryMessageProducerResponse constructor.
     *
     * @param int    $sentMessages
     * @param bool   $success
     * @param mixed  $originalResponse
     * @param string $messageId
     */
    public function __construct(int $sentMessages, bool $success, $originalResponse, string $messageId = '')
    {
        $this->sentMessages = $sentMessages;
        $this->success = $success;
        $this->originalResponse = $originalResponse;
        $this->messageId = $messageId;
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

    public function sentMessageId(): mixed
    {
        return $this->messageId ?: Uuid::uuid4();
    }
}
