<?php

namespace DDDStarterPack\Infrastructure\Application\Notification\InMemory;

use DDDStarterPack\Application\Notification\Message;
use DDDStarterPack\Application\Notification\MessageProducer;
use DDDStarterPack\Application\Notification\MessageProducerResponse;

class InMemoryMessageProducer implements MessageProducer
{
    const BATCH_LIMIT = 10;

    private $messageQueue;

    public function __construct(InMemoryMessageQueue $messageQueue)
    {
        $this->messageQueue = $messageQueue;
    }

    public function open(string $exchangeName)
    {

    }

    public function send(Message $message): MessageProducerResponse
    {
        $message['body'] = $message->getNotificationBodyMessage();
        $message['type'] = $message->getNotificationType();
        $message['occurred_on'] = $message->getNotificationOccurredOn();
        $message['notification_id'] = $message->getNotificationId();

        $serializedMessage = json_encode($message);

        $this->messageQueue->appendMessage($serializedMessage);
    }

    public function close($exchangeName)
    {

    }

    /**
     * @param \ArrayObject|Message[] $messages
     * @return MessageProducerResponse
     */
    public function sendBatch(\ArrayObject $messages): MessageProducerResponse
    {
        if ($messages->count() > self::BATCH_LIMIT) {

            $max = self::BATCH_LIMIT;
            throw new \InvalidArgumentException("Too many messages in batch. {$messages->count()} on $max permitted");
        }

        $messages = $messages->getArrayCopy();

        foreach ($messages as $notification) {

            $this->messageQueue->appendMessage($notification);
        }

        return new InMemoryMessageProducerResponse($this->messageQueue->count(), null);
    }

    public function getBatchLimit(): int
    {
        return self::BATCH_LIMIT;
    }
}
