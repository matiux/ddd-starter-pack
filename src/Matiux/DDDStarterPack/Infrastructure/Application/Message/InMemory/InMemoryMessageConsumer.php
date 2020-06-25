<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use ArrayObject;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageConsumer;

class InMemoryMessageConsumer implements MessageConsumer
{
    /** @var InMemoryMessageQueue */
    private $messageQueue;

    public function __construct(InMemoryMessageQueue $messageQueue)
    {
        $this->messageQueue = $messageQueue;
    }

    public function consume(): ?Message
    {
        return $this->messageQueue->popMessage();
    }

    public function consumeBatch(): array
    {
        return [];
    }

    /**
     * @param mixed $messageId
     */
    public function delete($messageId): void
    {
    }

    public function deleteBatch(ArrayObject $messagesId): void
    {
    }

    public function open(string $exchangeName = ''): void
    {
    }

    public function close(string $exchangeName = ''): void
    {
    }
}
