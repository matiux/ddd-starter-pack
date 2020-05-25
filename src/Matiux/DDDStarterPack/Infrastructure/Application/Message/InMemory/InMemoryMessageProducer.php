<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Application\Message\MessageProducerResponse;
use InvalidArgumentException;

class InMemoryMessageProducer implements MessageProducer
{
    const BATCH_LIMIT = 10;

    private $messageQueue;

    public function __construct(InMemoryMessageQueue $messageQueue)
    {
        $this->messageQueue = $messageQueue;
    }

    public function open(string $exchangeName = ''): void
    {
    }

    public function send(Message $message): MessageProducerResponse
    {
//        $payload['body'] = $message->body();
//        $payload['type'] = $message->type();
//        $payload['occurred_on'] = $message->occurredAt();
//        $payload['notification_id'] = $message->id();

        //$serializedMessage = json_encode($payload);

        $this->messageQueue->appendMessage($message);

        return new InMemoryMessageProducerResponse(1, true, []);
    }

    public function close(string $exchangeName = ''): void
    {
    }

    /**
     * @param Message[] $messages
     *
     * @return MessageProducerResponse
     */
    public function sendBatch(array $messages): MessageProducerResponse
    {
        if (count($messages) > self::BATCH_LIMIT) {
            $max = self::BATCH_LIMIT;

            throw new InvalidArgumentException(sprintf('Too many messages in batch. %s on {$max} permitted', count($messages)));
        }

        foreach ($messages as $message) {
            $this->messageQueue->appendMessage($message);
        }

        return new InMemoryMessageProducerResponse($this->messageQueue->count(), true, []);
    }

    public function getBatchLimit(): int
    {
        return self::BATCH_LIMIT;
    }
}
