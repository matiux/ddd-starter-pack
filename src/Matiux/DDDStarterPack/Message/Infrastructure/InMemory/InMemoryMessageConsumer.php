<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\InMemory;

use ArrayObject;
use DDDStarterPack\Message\Application\Message;
use DDDStarterPack\Message\Application\MessageConsumer;

class InMemoryMessageConsumer implements MessageConsumer
{
    public function __construct(
        private InMemoryMessageQueue $messageQueue
    ) {
    }

    public function consume(null|string $queue = null): null|Message
    {
        return $this->messageQueue->popMessage();
    }

    /**
     * @return Message[]
     * @codeCoverageIgnore
     */
    public function consumeBatch(): array
    {
        return [];
    }

    /**
     * @param mixed $messageId
     * @codeCoverageIgnore
     */
    public function delete(string $messageId, null|string $queue = null): void
    {
    }

    /**
     * @param ArrayObject $messagesId
     * @codeCoverageIgnore
     */
    public function deleteBatch(ArrayObject $messagesId): void
    {
    }

    /**
     * @param string $exchangeName
     * @codeCoverageIgnore
     */
    public function open(string $exchangeName = ''): void
    {
    }

    /**
     * @param string $exchangeName
     * @codeCoverageIgnore
     */
    public function close(string $exchangeName = ''): void
    {
    }
}
