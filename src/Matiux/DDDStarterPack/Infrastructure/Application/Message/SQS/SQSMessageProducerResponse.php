<?php

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use Aws\Result;
use DDDStarterPack\Application\Message\MessageProducerResponse;

class SQSMessageProducerResponse implements MessageProducerResponse
{
    public const SUCCESS_CODE = 200;

    private $sentMessages;
    private $originalResponse;

    public function __construct(int $sentMessages, Result $originalResponse)
    {
        $this->sentMessages = $sentMessages;
        $this->originalResponse = $originalResponse;
    }

    public function sentMessages(): int
    {
        return $this->sentMessages;
    }

    public function originalResponse(): Result
    {
        return $this->originalResponse;
    }

    public function body()
    {
        return $this->originalResponse->toArray();
    }

    public function sentMessageId()
    {
        return $this->originalResponse['MessageId'] ?? null;
    }

    public function isSuccess(): bool
    {
        return self::SUCCESS_CODE === $this->originalResponse['@metadata']['statusCode'];
    }
}
