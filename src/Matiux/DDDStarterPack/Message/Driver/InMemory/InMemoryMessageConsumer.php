<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\InMemory;

use DDDStarterPack\Message\Message;
use DDDStarterPack\Message\MessageConsumer;

class InMemoryMessageConsumer implements MessageConsumer
{
    public function __construct(
        private InMemoryMessageQueue $messageQueue,
    ) {}

    #[\Override]
    public function consume(null|string $queue = null): null|Message
    {
        return $this->messageQueue->popMessage();
    }

    /**
     * @return Message[]
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function consumeBatch(null|string $queue = null, int $maxNumberOfMessages = 1): array
    {
        return [];
    }

    /**
     * @param mixed $messageId
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function delete(string $messageId, null|string $queue = null): void {}

    /**
     * @param \ArrayObject $messagesId
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function deleteBatch(\ArrayObject $messagesId): void {}

    /**
     * @param string $exchangeName
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function open(string $exchangeName = ''): void {}

    /**
     * @param string $exchangeName
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function close(string $exchangeName = ''): void {}
}
