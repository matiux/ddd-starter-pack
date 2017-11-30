<?php

namespace DddStarterPack\Infrastructure\Application\Notification\InMemory;

use DddStarterPack\Application\Notification\MessageConsumer;

class InMemoryMessageConsumer implements MessageConsumer
{
    private $messageQueue;

    public function __construct(InMemoryMessageQueue $messageQueue)
    {

        $this->messageQueue = $messageQueue;
    }

    public function open(string $exchangeName)
    {

    }

    public function receiveMessage()
    {
        $message = $this->messageQueue->popMessage();

        return json_decode($message);
    }

    public function close($exchangeName)
    {

    }
}
