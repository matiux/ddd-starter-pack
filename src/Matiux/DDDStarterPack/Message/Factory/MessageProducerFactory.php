<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Factory;

use DDDStarterPack\Message\Configuration\Configuration;
use DDDStarterPack\Message\Driver\AWS\SNS\SNSMessagePublisher;
use DDDStarterPack\Message\Driver\AWS\SQS\SQSMessageProducer;
use DDDStarterPack\Message\MessageProducer;
use DDDStarterPack\Message\MessageProducerConnector;

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
            SNSMessagePublisher::NAME => new SNSMessagePublisher(),
            default => throw new \RuntimeException(sprintf('Invalid driver name [%s]', $configuration->getDriverName())),
        };
    }
}
