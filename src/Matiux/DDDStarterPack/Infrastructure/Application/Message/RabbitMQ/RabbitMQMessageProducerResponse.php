<?php

namespace DDDStarterPack\Infrastructure\Application\Message\RabbitMQ;

use DDDStarterPack\Application\Message\MessageProducerResponse;

class RabbitMQMessageProducerResponse implements MessageProducerResponse
{
    private $sentMessages;
    private $originalResponse;

    public function __construct(int $sentMessages, array $originalResponse)
    {
        $this->sentMessages = $sentMessages;
        $this->originalResponse = $originalResponse;
    }

    public function sentMessages(): int
    {
        return $this->sentMessages;
    }

    public function originalResponse()
    {
        return $this->originalResponse;
    }

    public function response()
    {
        return $this->originalResponse['Body'];
    }

    public function sentMessageId()
    {
        return $this->originalResponse['ReceiptHandle'];
    }
}
