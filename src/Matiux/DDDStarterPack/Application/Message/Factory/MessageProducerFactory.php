<?php

namespace DDDStarterPack\Application\Message\Factory;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Application\Message\MessageProducerConnector;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageProducer;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageService;
use RuntimeException;

class MessageProducerFactory
{
    public static function create(): self
    {
        return new  self();
    }

    public function obtainProducer(Configuration $configuration): MessageProducer
    {
        $messageProducer = $this->getMessageProducerOrFail($configuration);
        $messageProducer->bootstrap($configuration);

        return $messageProducer;
    }

    private function getMessageProducerOrFail(Configuration $configuration): MessageProducerConnector
    {
        switch ($configuration->getDriverName()) {
            case SQSMessageService::NAME:
                return new SQSMessageProducer();
                break;
            default:
                throw new RuntimeException(sprintf("Invalid driver name [%s]", $configuration->getDriverName()));
                break;
        }
    }
}
