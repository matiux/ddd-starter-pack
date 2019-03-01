<?php

namespace DDDStarterPack\Infrastructure\Application\Message\InMemory;

use DDDStarterPack\Application\Message\MessageProducerResponse;

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
