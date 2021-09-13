<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message\Factory;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\MessageConsumer;
use DDDStarterPack\Application\Message\MessageConsumerConnector;
use DDDStarterPack\Infrastructure\Application\Message\AWS\AWSMessageFactory;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\SQSMessageConsumer;
use RuntimeException;

class MessageConsumerFactory
{
    public static function create(): self
    {
        return new self();
    }

    public function obtainConsumer(Configuration $configuration): MessageConsumer
    {
        $messageConsumer = $this->getMessageConsumerOrFail($configuration);
        $messageConsumer->bootstrap($configuration);

        return $messageConsumer;
    }

    private function getMessageConsumerOrFail(Configuration $configuration): MessageConsumerConnector
    {
        return match ($configuration->getDriverName()) {
            SQSMessageConsumer::NAME => new SQSMessageConsumer(new AWSMessageFactory()),
            default => throw new RuntimeException(sprintf('Invalid driver name [%s]', $configuration->getDriverName())),
        };
    }
}
