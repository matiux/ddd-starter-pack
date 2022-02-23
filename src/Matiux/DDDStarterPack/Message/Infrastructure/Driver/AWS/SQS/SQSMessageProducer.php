<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS;

use Aws\Result;
use BadMethodCallException;
use DateTimeInterface;
use DDDStarterPack\Message\Infrastructure\BasicMessageService;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\AWSBasicService;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\AWSMessage;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\AWSMessageProducerResponse;
use DDDStarterPack\Message\Infrastructure\Exception\MessageInvalidException;
use DDDStarterPack\Message\Infrastructure\Message;
use DDDStarterPack\Message\Infrastructure\MessageProducerConnector;
use DDDStarterPack\Message\Infrastructure\MessageProducerResponse;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @implements MessageProducerConnector<AWSMessage>
 */
class SQSMessageProducer extends BasicMessageService implements MessageProducerConnector
{
    use AWSBasicService;
    use SQSBasicService;

    public const NAME = 'SQS';

    public function send($message): MessageProducerResponse
    {
        return $this->doSend($message);
    }

    /**
     * @param array $messages
     *
     * @return MessageProducerResponse
     * @codeCoverageIgnore
     */
    public function sendBatch(array $messages): MessageProducerResponse
    {
        throw new BadMethodCallException();
    }

    /**
     * @return int
     * @codeCoverageIgnore
     */
    public function getBatchLimit(): int
    {
        throw new BadMethodCallException();
    }

    /**
     * @psalm-suppress MixedAssignment
     *
     * @param array $extra
     *
     * @return array
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
                case 'QueueUrl':
                case 'MessageDeduplicationId':
                case 'ContentBasedDeduplication':
                case 'MessageGroupId':
                    /**
                     * MessageDeduplicationId - obbligatorio per le code FIFO (a meno che non sia attivata sulla coda la generazione automatica)
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

    private function doSend(AWSMessage $message): MessageProducerResponse
    {
        $messageAttributes = $this->createMessageAttributes($message);

        $extra = $this->parseExtra($message->extra());

        $args = $extra + [
            'MessageBody' => $message->body(),
            'MessageAttributes' => $messageAttributes,
        ];

        if (!array_key_exists('QueueUrl', $args)) {
            $args['QueueUrl'] = $this->getQueueUrlFromConfig();
        }

        $result = $this->getClient()->sendMessage($args);

        if (!$this->isValidSent($message, $result)) {
            throw new MessageInvalidException('Message sent but corrupt');
        }

        return new AWSMessageProducerResponse(1, $result);
    }

    private function createMessageAttributes(AWSMessage $message): array
    {
        $messageAttributes = [];

        if ($occurredAt = $message->occurredAt()) {
            $messageAttributes['OccurredAt'] = [
                'DataType' => 'String',
                'StringValue' => $occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ];
        }

        if ($message->type()) {
            $messageAttributes['Type'] = [
                'DataType' => 'String',
                'StringValue' => (string) $message->type(),
            ];
        }

        if (array_key_exists('MessageAttributes', $message->extra())) {
            $messageAttributes += (array) $message->extra()['MessageAttributes'];
        }

        return $messageAttributes;
    }

    private function isValidSent(Message $message, Result $result): bool
    {
        return md5($message->body()) === $result['MD5OfMessageBody'];
    }
}
