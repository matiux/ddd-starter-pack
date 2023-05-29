<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Driver\AWS\SQS;

use Aws\Result;
use DDDStarterPack\Message\BasicMessageService;
use DDDStarterPack\Message\Driver\AWS\AWSBasicService;
use DDDStarterPack\Message\Driver\AWS\AWSMessage;
use DDDStarterPack\Message\Driver\AWS\AWSMessageProducerResponse;
use DDDStarterPack\Message\Exception\MessageInvalidException;
use DDDStarterPack\Message\Message;
use DDDStarterPack\Message\MessageProducerConnector;
use DDDStarterPack\Message\MessageProducerResponse;
use Webmozart\Assert\Assert;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 *
 * @implements MessageProducerConnector<AWSMessage>
 */
class SQSMessageProducer extends BasicMessageService implements MessageProducerConnector
{
    use AWSBasicService;
    use SQSBasicService;

    public const NAME = 'SQS';

    /**
     * {@inheritDoc}
     */
    public function send($message): MessageProducerResponse
    {
        return $this->doSend($message);
    }

    private function doSend(AWSMessage $AWSMessage): MessageProducerResponse
    {
        $result = $this->getClient()->sendMessage(
            $this->prepareMessage($AWSMessage),
        );

        if (!$this->isValidSent($AWSMessage, $result)) {
            throw new MessageInvalidException('Message sent but corrupt');
        }

        return new AWSMessageProducerResponse(1, $result);
    }

    private function prepareMessage(AWSMessage $AWSMessage): array
    {
        $extraAttributes = $this->extractExtraAttributesFromMessage($AWSMessage);
        $attributes = $this->extractAttributesFromMessage($AWSMessage);

        $result = $extraAttributes + [
            'MessageBody' => $AWSMessage->body(),
            'MessageAttributes' => $attributes,
        ];

        if (!array_key_exists('QueueUrl', $result)) {
            $result['QueueUrl'] = $this->getQueueUrlFromConfig();
        }

        return $result;
    }

    /**
     * @psalm-suppress MixedAssignment
     *
     * @param AWSMessage $AWSMessage
     *
     * @return array
     */
    protected function extractExtraAttributesFromMessage(AWSMessage $AWSMessage): array
    {
        if (empty($AWSMessage->extra())) {
            return [];
        }

        $extra = $AWSMessage->extra();

        /** @psalm-var array<array-key, mixed> $result */
        $result = [];

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
                    $result[$key] = $value;

                    break;
            }
        }

        return $result;
    }

    private function extractAttributesFromMessage(AWSMessage $message): array
    {
        $messageAttributes = [];

        if ($occurredAt = $message->occurredAt()) {
            $messageAttributes['OccurredAt'] = [
                'DataType' => 'String',
                'StringValue' => $occurredAt->format(\DateTimeInterface::RFC3339_EXTENDED),
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

    /**
     * {@inheritDoc}
     */
    public function sendBatch(array $messages): MessageProducerResponse
    {
        $toSendMessages = [];

        foreach ($messages as $message) {
            $toSendMessages[] = $this->prepareBatchMessage($message);
        }

        $result = $this->getClient()->sendMessageBatch([
            'QueueUrl' => $this->getQueueUrlFromConfig(),
            'Entries' => $toSendMessages,
        ]);

        Assert::isArray($result['Successful']);

        return new AWSMessageProducerResponse(count($result['Successful']), $result);
    }

    private function prepareBatchMessage(AWSMessage $message): array
    {
        $result = $this->prepareMessage($message);

        $result['Id'] = $message->id() ?? md5($message->body());
        unset($result['QueueUrl']);

        return $result;
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getBatchLimit(): int
    {
        throw new \BadMethodCallException();
    }

    private function isValidSent(Message $message, Result $result): bool
    {
        return md5($message->body()) === $result['MD5OfMessageBody'];
    }
}
