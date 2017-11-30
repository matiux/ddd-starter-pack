<?php

namespace DddStarterPack\Infrastructure\Application\Notification\InMemory;

use DddStarterPack\Application\Notification\MessageProducer;

class InMemoryMessageProducer implements MessageProducer
{
    private $messageQueue;

    public function __construct(InMemoryMessageQueue $messageQueue)
    {
        $this->messageQueue = $messageQueue;
    }

    public function open(string $exchangeName)
    {

    }

    public function send(
        string $exchangeName,
        string $notificationMessage,
        string $notificationType,
        int $notificationId,
        \DateTimeInterface $notificationOccurredOn
    )
    {
        $message['body'] = $notificationMessage;
        $message['type'] = $notificationType;
        $message['occured_on'] = $notificationOccurredOn;
        $message['notification_id'] = $notificationId;

        $serializedMessage = json_encode($message);

        $this->messageQueue->appendMessage($serializedMessage);
    }

    public function close($exchangeName)
    {

    }
}
