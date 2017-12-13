<?php

namespace DddStarterPack\Infrastructure\Application\Notification\InMemory;

use DDDStarterPack\Application\Notification\MessageProducerResponse;

class InMemoryMessageProducerResponse implements MessageProducerResponse
{
    private $sentMessages;
    private $originalResponse;

    public function __construct(int $sentMessages, $originalResponse)
    {
        $this->sentMessages = $sentMessages;
        $this->originalResponse = $originalResponse;
    }

    public function getSentMessages(): int
    {
        return $this->sentMessages;
    }

    public function getOriginalResponse()
    {
        return $this->originalResponse;
    }
}
