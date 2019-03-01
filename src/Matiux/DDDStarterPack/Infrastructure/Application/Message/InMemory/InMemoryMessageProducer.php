<?php

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use ArrayObject;
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
        $message['body'] = $message->body();
        $message['type'] = $message->type();
        $message['occurred_on'] = $message->occurredAt();
        $message['notification_id'] = $message->id();

        $serializedMessage = json_encode($message);

        $this->messageQueue->appendMessage($serializedMessage);
    }

    public function close(string $exchangeName = ''): void
    {

    }

    /**
     * @param ArrayObject|Message[] $messages
     * @return MessageProducerResponse
     */
    public function sendBatch(ArrayObject $messages): MessageProducerResponse
    {
        if ($messages->count() > self::BATCH_LIMIT) {

            $max = self::BATCH_LIMIT;
            throw new InvalidArgumentException("Too many messages in batch. {$messages->count()} on $max permitted");
        }

        $notifications = array_map(function (Message $notificationBodyMessage) {

            return $notificationBodyMessage->body();

        }, $messages->getArrayCopy());

        foreach ($notifications as $notification) {

            $this->messageQueue->appendMessage($notification);
        }

        return new InMemoryMessageProducerResponse($this->messageQueue->count(), null);
    }

    public function getBatchLimit(): int
    {
        return self::BATCH_LIMIT;
    }
}
