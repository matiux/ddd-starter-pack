<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\InMemory;

use DDDStarterPack\Message\Message;
use DDDStarterPack\Message\MessageProducer;
use DDDStarterPack\Message\MessageProducerResponse;

/**
 * @implements MessageProducer<InMemoryMessage>
 */
class InMemoryMessageProducer implements MessageProducer
{
    public const BATCH_LIMIT = 10;

    public function __construct(
        private InMemoryMessageQueue $messageQueue,
    ) {}

    /**
     * @param string $exchangeName
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function open(string $exchangeName = ''): void {}

    #[\Override]
    public function send($message): MessageProducerResponse
    {
        $this->messageQueue->appendMessage($message);

        $id = null;

        if ($message instanceof Message) {
            $id = (string) $message->id();
        }

        return new InMemoryMessageProducerResponse(sentMessages: 1, success: true, originalResponse: [], messageId: $id);
    }

    /**
     * @param string $exchangeName
     *
     * @codeCoverageIgnore
     */
    #[\Override]
    public function close(string $exchangeName = ''): void {}

    /**
     * @param InMemoryMessage[] $messages
     *
     * @return MessageProducerResponse
     */
    #[\Override]
    public function sendBatch(array $messages): MessageProducerResponse
    {
        if (count($messages) > self::BATCH_LIMIT) {
            throw new \InvalidArgumentException(sprintf('Too many messages in batch. %s on {$max} permitted', count($messages)));
        }

        foreach ($messages as $message) {
            $this->messageQueue->appendMessage($message);
        }

        return new InMemoryMessageProducerResponse($this->messageQueue->count(), true, []);
    }

    #[\Override]
    public function getBatchLimit(): int
    {
        return self::BATCH_LIMIT;
    }
}
