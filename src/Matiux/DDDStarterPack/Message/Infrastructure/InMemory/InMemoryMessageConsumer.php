<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\InMemory;

use ArrayObject;
use DDDStarterPack\Message\Application\Message;
use DDDStarterPack\Message\Application\MessageConsumer;

class InMemoryMessageConsumer implements MessageConsumer
{
    /** @var InMemoryMessageQueue */
    private $messageQueue;

    public function __construct(InMemoryMessageQueue $messageQueue)
    {
        $this->messageQueue = $messageQueue;
    }

    public function consume(null|string $queue = null): null|Message
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
    public function delete(string $messageId, null|string $queue = null): void
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
