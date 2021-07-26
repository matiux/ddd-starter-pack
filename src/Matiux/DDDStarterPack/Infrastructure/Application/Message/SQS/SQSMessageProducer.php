<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\SQS;

use Aws\Result;
use DDDStarterPack\Application\Message\Exception\MessageInvalidException;
use DDDStarterPack\Application\Message\Message;
use DDDStarterPack\Application\Message\MessageProducerConnector;
use DDDStarterPack\Application\Message\MessageProducerResponse;
use Webmozart\Assert\Assert;

/**
 * @implements MessageProducerConnector<SQSMessage>
 */
final class SQSMessageProducer extends SQSMessageService implements MessageProducerConnector
{
    public const BATCH_LIMIT = 10;

    /**
     * @param SQSMessage $message
     *
     * @throws MessageInvalidException
     *
     * @return MessageProducerResponse
     */
    public function send($message): MessageProducerResponse
    {
        return $this->doSend($message);
    }

    /**
     * @param SQSMessage[] $messages
     *
     * @return MessageProducerResponse
     */
    public function sendBatch(array $messages): MessageProducerResponse
    {
        $entries = [];

        foreach ($messages as $message) {
            $entries[] = [
                'Id' => $message->id() ?? md5($message->body()),
                'MessageAttributes' => $this->createMessageAttributes($message),
                'MessageBody' => $message->body(),
            ];
        }

        $result = $this->getClient()->sendMessageBatch([
            'QueueUrl' => $this->getQueueUrl(),
            'Entries' => $entries,
        ]);

        Assert::isArray($result['Successful']);

        $sentMessages = count($result['Successful']);

        return new SQSMessageProducerResponse($sentMessages, $result);
    }

    public function getBatchLimit(): int
    {
        return self::BATCH_LIMIT;
    }

    /**
     * @psalm-suppress MixedAssignment
     *
     * @param array<array-key, mixed> $extra
     *
     * @return array<array-key, mixed>
     */
    protected function parseExtra(array $extra): array
    {
        if (empty($extra)) {
            return [];
        }

        /** @psalm-var array<array-key, mixed> $e */
        $e = [];

        foreach ($extra as $key => $value) {
            switch ($key) {
                case 'MessageDeduplicationId':
                case 'ContentBasedDeduplication':
                case 'MessageGroupId':
                    /**
                     * MessageDeduplicationId - obbligatorio per le code FIFO
                     * ContentBasedDeduplication - obbligatorio per le code FIFO
                     * MessageGroupId - obbligatorio per le code FIFO
                     * https://docs.aws.amazon.com/aws-sdk-php/v3/api/api-sqs-2012-11-05.html#sendmessage
                     * https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/FIFO-queues.html#FIFO-queues-exactly-once-processing
                     * https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/using-messagegroupid-property.html.
                     */
                    $e[$key] = $value;

                    break;
            }
        }

        return $e;
    }

    private function doSend(SQSMessage $message): SQSMessageProducerResponse
    {
        $messageAttributes = $this->createMessageAttributes($message);

        $extra = $this->parseExtra($message->extra());

        $args = $extra + [
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => $message->body(),
            'MessageAttributes' => $messageAttributes,
        ];

        $result = $this->getClient()->sendMessage($args);

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

    private function isValidSent(Message $message, Result $result): bool
    {
        return md5($message->body()) === $result['MD5OfMessageBody'];
    }
}
