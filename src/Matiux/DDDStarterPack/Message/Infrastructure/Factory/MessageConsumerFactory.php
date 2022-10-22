<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Factory;

use DDDStarterPack\Message\Infrastructure\Configuration\Configuration;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\AWSMessageFactory;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS\SQSMessageConsumer;
use DDDStarterPack\Message\Infrastructure\MessageConsumer;
use DDDStarterPack\Message\Infrastructure\MessageConsumerConnector;
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
        // TODO -> No buono parlare di infrastruttura qui
        return match ($configuration->getDriverName()) {
            SQSMessageConsumer::NAME => new SQSMessageConsumer(new AWSMessageFactory()),
            default => throw new RuntimeException(sprintf('Invalid driver name [%s]', $configuration->getDriverName())),
        };
    }
}