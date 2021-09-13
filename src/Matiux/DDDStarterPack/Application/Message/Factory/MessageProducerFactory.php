<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message\Factory;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Application\Message\MessageProducerConnector;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SNS\SNSMessagePubblisher;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\SQSMessageProducer;
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
