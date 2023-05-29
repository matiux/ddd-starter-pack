<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Factory;

use DDDStarterPack\Message\Configuration\Configuration;
use DDDStarterPack\Message\Driver\AWS\AWSMessageFactory;
use DDDStarterPack\Message\Driver\AWS\SQS\SQSMessageConsumer;
use DDDStarterPack\Message\MessageConsumer;
use DDDStarterPack\Message\MessageConsumerConnector;

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
            default => throw new \RuntimeException(sprintf('Invalid driver name [%s]', $configuration->getDriverName())),
        };
    }
}
