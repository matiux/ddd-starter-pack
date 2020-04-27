<?php

namespace DDDStarterPack\Application\Message\Factory;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\MessageConsumer;
use DDDStarterPack\Application\Message\MessageConsumerConnector;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageConsumer;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageFactory;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageService;
use RuntimeException;

class MessageConsumerFactory
{
    public static function create(): self
    {
        return new  self();
    }

    public function obtainConsumer(Configuration $configuration): MessageConsumer
    {
        $messageConsumer = $this->getMessageConsumerOrFail($configuration);
        $messageConsumer->bootstrap($configuration);

        return $messageConsumer;
    }

    private function getMessageConsumerOrFail(Configuration $configuration): MessageConsumerConnector
    {
        switch ($configuration->getDriverName()) {
            case SQSMessageService::NAME:
                return new SQSMessageConsumer(new SQSMessageFactory());
                break;
            default:
                throw new RuntimeException(sprintf("Invalid driver name [%s]", $configuration->getDriverName()));
                break;
        }
    }
}
