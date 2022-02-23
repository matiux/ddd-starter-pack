<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Factory;

use DDDStarterPack\Message\Infrastructure\Configuration\Configuration;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\SNS\SNSMessagePubblisher;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS\SQSMessageProducer;
use DDDStarterPack\Message\Infrastructure\MessageProducer;
use DDDStarterPack\Message\Infrastructure\MessageProducerConnector;
use RuntimeException;

class MessageProducerFactory
{
    public static function create(): self
    {
        return new self();
    }

    public function obtainProducer(Configuration $configuration): MessageProducer
    {
        $messageProducer = $this->getMessageProducerOrFail($configuration);
        $messageProducer->bootstrap($configuration);

        return $messageProducer;
    }

    private function getMessageProducerOrFail(Configuration $configuration): MessageProducerConnector
    {
        // TODO -> No buono parlare di infrastruttura qui
        return match ($configuration->getDriverName()) {
            SQSMessageProducer::NAME => new SQSMessageProducer(),
            SNSMessagePubblisher::NAME => new SNSMessagePubblisher(),
            default => throw new RuntimeException(sprintf('Invalid driver name [%s]', $configuration->getDriverName())),
        };
    }
}
