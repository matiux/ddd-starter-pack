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
        $message['occured_on'] = $message->getNotificationOccuredOn();
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

    }

    public function getBatchLimit(): int
    {
        return self::BATCH_LIMIT;
    }
}
