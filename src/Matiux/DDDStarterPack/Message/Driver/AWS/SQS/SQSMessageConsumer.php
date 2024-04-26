<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SQS;

use Aws\Result;
use DDDStarterPack\Message\BasicMessageService;
use DDDStarterPack\Message\Driver\AWS\AWSBasicService;
use DDDStarterPack\Message\Driver\AWS\AWSMessage;
use DDDStarterPack\Message\Driver\AWS\AWSMessageFactory;
use DDDStarterPack\Message\Message;
use DDDStarterPack\Message\MessageConsumerConnector;
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
    private array $attributeNames = ['All']; // 'ApproximateReceiveCount'

    /** @var string[] */
    private array $messageAttributeNames = ['All']; // 'Type', 'OccurredAt'

    public function __construct(
        private AWSMessageFactory $AWSMessageFactory,
    ) {
        parent::__construct();
    }

    public function consume(null|string $queue = null): null|Message
    {
        $messages = $this->doConsume($queue, 1);

        return !empty($messages) ? current($messages) : null;
    }

    /** @return AWSMessage[] */
    private function doConsume(null|string $queue, int $maxNumberOfMessages): array
    {
        $queue ??= $this->getQueueUrlFromConfig();

        if (10 < $maxNumberOfMessages) {
            throw new \InvalidArgumentException('Exceed max batch size of 10 messages');
        }

        $args = [
            'QueueUrl' => $queue,
            'AttributeNames' => $this->attributeNames,
            'MessageAttributeNames' => $this->messageAttributeNames,
            'MaxNumberOfMessages' => $maxNumberOfMessages,
        ];

        $response = $this->getClient()->receiveMessage($args);

        return $this->extractSqsMessagesFromResponse($response);
    }

    /** @return AWSMessage[] */
    private function extractSqsMessagesFromResponse(Result $response): array
    {
        if (null === $response['Messages']) {
            return [];
        }

        Assert::isArray($response['Messages']);

        $sqsMessages = [];

        /** @var array $message */
        foreach ($response['Messages'] as $message) {
            [$body, $messageAttributes, $attributes] = $this->parseMessage($message);

            $type = $this->extractType($messageAttributes);
            $occurredAt = $this->extractOccurredAt($messageAttributes);

            $sqsMessages[] = $this->AWSMessageFactory->build(
                body: $body,
                occurredAt: $occurredAt,
                type: $type,
                id: (string) $message['ReceiptHandle'],
                extra: [
                    'MessageAttributes' => $messageAttributes,
                    'Attributes' => $attributes,
                ],
            );
        }

        return $sqsMessages;
    }

    /**
     * @param array $message
     *
     * @return array{0: string, 1: array, 2: array}
     */
    private function parseMessage(array $message): array
    {
        $originalBody = (array) json_decode((string) $message['Body'], true);

        if ($this->isSnsRawMessageDeliveryEnabled($originalBody)) {
            $body = (string) $originalBody['Message'];
            $messageAttributes = (array) ($originalBody['MessageAttributes'] ?? []);
        } else {
            $body = (string) json_encode($originalBody);
            $messageAttributes = (array) ($message['MessageAttributes'] ?? []);
        }

        $messageAttributes = $this->prepareMessageAttributes($messageAttributes);

        $attributes = (array) ($message['Attributes'] ?? []);

        return [$body, $messageAttributes, $attributes];
    }

    private function isSnsRawMessageDeliveryEnabled(array $originalBody): bool
    {
        return array_key_exists('MessageId', $originalBody) && array_key_exists('Message', $originalBody);
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

    private function extractOccurredAt(array $messageAttributes): null|\DateTimeImmutable
    {
        if (!array_key_exists('OccurredAt', $messageAttributes)) {
            return null;
        }

        $messageAttributesOccurredAt = (array) $messageAttributes['OccurredAt'];

        /** @var array{Value?: string} $messageAttributesOccurredAt */
        $occurredAt = $messageAttributesOccurredAt['Value'] ?? null;

        return match ($occurredAt) {
            null => null,
            default => new \DateTimeImmutable($occurredAt),
        };
    }

    /**
     * {@inheritDoc}
     */
    public function consumeBatch(null|string $queue = null, int $maxNumberOfMessages = 1): array
    {
        return $this->doConsume($queue, $maxNumberOfMessages);
    }

    public function delete(string $messageId, null|string $queue = null): void
    {
        $queue ??= $this->getQueueUrlFromConfig();

        $result = $this->getClient()->deleteMessage([
            'QueueUrl' => $queue,
            'ReceiptHandle' => $messageId,
        ]);

        if (!self::isAwsResultValid($result)) {
            throw new \Exception(sprintf('Unable to delete message with id %s from %s', $messageId, $this->getQueueUrlFromConfig()));
        }
    }

    public function deleteBatch(\ArrayObject $messagesId): void
    {
        throw new \BadMethodCallException();
    }
}
