<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\AWS;

use Aws\Result;
use DDDStarterPack\Message\Application\MessageProducerResponse;
use Webmozart\Assert\Assert;

class AWSMessageProducerResponse implements MessageProducerResponse
{
    public const SUCCESS_CODE = 200;

    public function __construct(
        private int $sentMessages,
        private Result $originalResponse,
    ) {
    }

    public function sentMessages(): int
    {
        return $this->sentMessages;
    }

    public function originalResponse(): Result
    {
        return $this->originalResponse;
    }

    public function body(): mixed
    {
        return $this->originalResponse->toArray();
    }

    public function sentMessageId(): mixed
    {
        return $this->originalResponse['MessageId'] ?? null;
    }

    public function isSuccess(): bool
    {
        Assert::notEmpty($this->originalResponse);
        Assert::true(isset($this->originalResponse['@metadata']));
        Assert::isArray($this->originalResponse['@metadata']);
        Assert::notEmpty($this->originalResponse['@metadata']);
        Assert::true(isset($this->originalResponse['@metadata']['statusCode']));

        return self::SUCCESS_CODE === $this->originalResponse['@metadata']['statusCode'];
    }
}
