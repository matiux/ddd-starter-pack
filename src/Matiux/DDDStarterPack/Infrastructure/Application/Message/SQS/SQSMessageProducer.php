<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use Aws\Result;
use DDDStarterPack\Application\Message\Exception\MessageInvalidException;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageProducerConnector;
use DDDStarterPack\Application\Message\MessageProducerResponse;
use Webmozart\Assert\Assert;

final class SQSMessageProducer extends SQSMessageService implements MessageProducerConnector
{
    const BATCH_LIMIT = 10;

    public function send(Message $message): MessageProducerResponse
    {
        Assert::isInstanceOf($message, SQSMessage::class);

        return $this->doSend($message);
    }

    private function doSend(SQSMessage $message): SQSMessageProducerResponse
    {
        $messageAttributes = $this->createMessageAttributes($message);

        $result = $this->getClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => $message->body(),
            'MessageAttributes' => $messageAttributes,
        ]);

        if (!$this->isValidSent($message, $result)) {
            throw new MessageInvalidException('Message sent but corrupt');
        }

        return new SQSMessageProducerResponse(1, $result);
    }

    private function createMessageAttributes(SQSMessage $message): array
    {
        $messageAttributes = [
            'OccurredAt' => [
                'DataType' => 'String',
                'StringValue' => $message->occurredAt()->format('Y-m-d H:i:s'),
            ],
        ];

        if ($message->type()) {
            $messageAttributes['Type'] = [
                'DataType' => 'String',
                'StringValue' => $message->type(),
            ];
        }

        return $messageAttributes;
    }

//    private function getQueueUrl(string $exchangeName)
//    {
//        if (!array_key_exists($exchangeName, $this->urls)) {
//
//            $url = $this->getClient()->getQueueUrl([
//                'QueueName' => $exchangeName
//            ]);
//
//            $this->urls[$exchangeName] = $url['QueueUrl'];
//        }
//
//        return $this->urls[$exchangeName];
//    }

    private function isValidSent(Message $message, Result $result): bool
    {
        return md5($message->body()) === $result['MD5OfMessageBody'];
    }

    /**
     * @psalm-suppress InvalidReturnType
     *
     * @param Message[] $messages
     *
     * @return MessageProducerResponse
     */
    public function sendBatch(array $messages): MessageProducerResponse
    {
    }

    public function getBatchLimit(): int
    {
        return self::BATCH_LIMIT;
    }
}
