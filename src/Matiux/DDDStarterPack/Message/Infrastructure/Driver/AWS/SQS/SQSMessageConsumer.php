<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure\Driver\AWS\SQS;

use ArrayObject;
use Aws\Result;
use BadMethodCallException;
use DateTimeImmutable;
use DDDStarterPack\Message\Infrastructure\BasicMessageService;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\AWSBasicService;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\AWSMessage;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\AWSMessageFactory;
use DDDStarterPack\Message\Infrastructure\Message;
use DDDStarterPack\Message\Infrastructure\MessageConsumerConnector;
use Exception;
use Webmozart\Assert\Assert;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SQSMessageConsumer extends BasicMessageService implements MessageConsumerConnector
{
    use AWSBasicService;
    use SQSBasicService;

    public const NAME = 'SQS';

    /** @var string[] */
    private array $attributeNames = ['All']; //'ApproximateReceiveCount'

    /** @var string[] */
    private array $messageAttributeNames = ['All']; //'Type', 'OccurredAt'

    public function __construct(
        private AWSMessageFactory $AWSMessageFactory
    ) {
        parent::__construct();
    }

    public function consume(null|string $queue = null): null|Message
    {
        $queue ??= $this->getQueueUrlFromConfig();

        $args = [
            'QueueUrl' => $queue,
            'AttributeNames' => $this->attributeNames,
            'MessageAttributeNames' => $this->messageAttributeNames,
        ];

        $response = $this->getClient()->receiveMessage($args);

        return $this->extractSqsMessageFromResponse($response);
    }

    private function extractSqsMessageFromResponse(Result $response): null|AWSMessage
    {
        if (empty($response['Messages'])) {
            return null;
        }

        Assert::isArray($response['Messages']);
        $message = (array) $response['Messages'][0];

        [$body, $messageAttributes] = $this->parseMessage($message);

        $type = $this->extractType($messageAttributes);
        $occurredAt = $this->extractOccurredAt($messageAttributes);

        return $this->AWSMessageFactory->build(
            body: $body,
            occurredAt: $occurredAt,
            type: $type,
            id: (string) $message['ReceiptHandle'],
            extra: ['MessageAttributes' => $messageAttributes],
        );
    }

    /**
     * @param array $message
     *
     * @return array{0: string, 1: array}
     */
    private function parseMessage(array $message): array
    {
        $body = (array) json_decode((string) $message['Body'], true);

        if (array_key_exists('MessageAttributes', $body)) {
            // Opzione della sottoscrizione "Enable raw message delivery" disabilitata
            $messageAttributes = (array) $body['MessageAttributes'];
            $body = (string) $body['Message'];
        } else {
            // Invio diretto di un messaggio su cosa SQS
            // Opzione della sottoscrizione "Enable raw message delivery" attivata
            $messageAttributes = (array) $message['MessageAttributes'];
            $body = (string) json_encode($body);
        }

        $messageAttributes = $this->prepareMessageAttributes($messageAttributes);

        return [$body, $messageAttributes];
    }

    /**
     * @param array $messageAttributes
     *
     * @return array
     */
    private function prepareMessageAttributes(array $messageAttributes): array
    {
        $attributes = [];

        /**
         * Opzione della sottoscrizione "Enable raw message delivery" attivata = DataType e StringValue
         * Opzione della sottoscrizione "Enable raw message delivery" disabilitata = Type e Value.
         *
         * @var string $name
         * @var array  $attribute
         */
        foreach ($messageAttributes as $name => $attribute) {
            $attributes[$name] = [
                'Type' => $attribute['DataType'] ?? $attribute['Type'],
                'Value' => $attribute['StringValue'] ?? $attribute['Value'],
            ];
        }

        return $attributes;
    }

    private function extractType(array $messageAttributes): null|string
    {
        if (!array_key_exists('Type', $messageAttributes)) {
            return null;
        }

        $messageAttributesType = (array) $messageAttributes['Type'];

        /** @var array{Value?: string} $messageAttributesType */
        return $messageAttributesType['Value'] ?? null;
    }

    private function extractOccurredAt(array $messageAttributes): null|DateTimeImmutable
    {
        if (!array_key_exists('OccurredAt', $messageAttributes)) {
            return null;
        }

        $messageAttributesOccurredAt = (array) $messageAttributes['OccurredAt'];

        /** @var array{Value?: string} $messageAttributesOccurredAt */
        $occurredAt = $messageAttributesOccurredAt['Value'] ?? null;

        return match ($occurredAt) {
            null => null,
            default => new DateTimeImmutable($occurredAt)
        };
    }

    /**
     * {@inheritDoc}
     */
    public function consumeBatch(): array
    {
        throw new BadMethodCallException();
    }

    public function delete(string $messageId, null|string $queue = null): void
    {
        $queue ??= $this->getQueueUrlFromConfig();

        $result = $this->getClient()->deleteMessage([
            'QueueUrl' => $queue,
            'ReceiptHandle' => $messageId,
        ]);

        if (!self::isAwsResultValid($result)) {
            throw new Exception(sprintf('Unable to delete message with id %s from %s', $messageId, $this->getQueueUrlFromConfig()));
        }
    }

    public function deleteBatch(ArrayObject $messagesId): void
    {
        throw new BadMethodCallException();
    }
}
