<?php

declare(strict_types=1);

namespace DDDStarterPack\Infrastructure\Application\Message\AWS\SNS;

use Aws\Sns\SnsClient;
use BadMethodCallException;
use DateTimeInterface;
use DDDStarterPack\Application\Message\BasicMessageService;
use DDDStarterPack\Application\Message\Configuration\Configuration;
use DDDStarterPack\Application\Message\Configuration\ConfigurationValidator;
use DDDStarterPack\Application\Message\Exception\MessageInvalidException;
use DDDStarterPack\Application\Message\MessageProducerConnector;
use DDDStarterPack\Application\Message\MessageProducerResponse;
use DDDStarterPack\Infrastructure\Application\Message\AWS\AWSBasicService;
use DDDStarterPack\Infrastructure\Application\Message\AWS\AWSMessage;
use DDDStarterPack\Infrastructure\Application\Message\AWS\AWSMessageProducerResponse;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SNS\Configuration\SNSConfiguration;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SNS\Configuration\SNSConfigurationValidator;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Webmozart\Assert\Assert;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 * @implements MessageProducerConnector<AWSMessage>
 */
class SNSMessagePubblisher extends BasicMessageService implements MessageProducerConnector
{
    use AWSBasicService;

    public const NAME = 'SNS';
    private SNSConfiguration $configuration;
    private null|string $topicArn = null;
    private null|SnsClient $client = null;

    protected function defaultsParams(): array
    {
        return $this->customDefaultsParams() + ['sns_topic_arn' => null];
    }

    protected function obtainConfigurationValidator(): ConfigurationValidator
    {
        return new SNSConfigurationValidator();
    }

    protected function setSpecificConfiguration(Configuration $configuration): void
    {
        /** @var string[] $params */
        $params = $configuration->getParams();

        $this->configuration = new SNSConfiguration(
            $params['region'],
            $params['access_key'] ?? null,
            $params['secret_key'] ?? null,
            $params['sns_topic_arn'] ?? null,
        );
    }

    public function send($message): MessageProducerResponse
    {
        return $this->doSend($message);
    }

    private function doSend(AWSMessage $message): MessageProducerResponse
    {
        $messageAttributes = $this->createMessageAttributes($message);

        $extra = $this->parseExtra($message->extra());

        $args = $extra + [
            'Message' => $message->body(),
            'MessageAttributes' => $messageAttributes,
        ];

        if (!array_key_exists('TopicArn', $args)) {
            $args['TopicArn'] = $this->getTopicArnFromConfig();
        }

        $result = $this->getClient()->publish($args);

        if (!self::isAwsResultValid($result)) {
            throw new MessageInvalidException('Message sent but corrupt');
        }

        return new AWSMessageProducerResponse(1, $result);
    }

    private function createMessageAttributes(AWSMessage $message): array
    {
        $messageAttributes = [
            'OccurredAt' => [
                'DataType' => 'String',
                'StringValue' => $message->occurredAt()->format(DateTimeInterface::RFC3339_EXTENDED),
            ],
        ];

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
                case 'TopicArn':
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

    private function getTopicArnFromConfig(): string
    {
        if (!isset($this->topicArn)) {
            $this->setTopicArnOrFail();
        }

        Assert::notNull($this->topicArn);

        return $this->topicArn;
    }

    private function setTopicArnOrFail(): void
    {
        $this->topicArn = $this->configuration->topicArn();

        if (!$this->topicArn || 0 === strlen(trim($this->topicArn))) {
            throw new InvalidConfigurationException('Topic Arn missing');
        }
    }

    protected function getClient(): SnsClient
    {
        if (!isset($this->client)) {
            $args = [
                'version' => 'latest',
                'region' => $this->configuration->region(),
                'debug' => false,
            ];

            $this->client = new SnsClient($args + $this->createCredentials());
        }

        return $this->client;
    }

    public function sendBatch(array $messages): MessageProducerResponse
    {
        throw new BadMethodCallException();
    }

    public function getBatchLimit(): int
    {
        throw new BadMethodCallException();
    }
}
