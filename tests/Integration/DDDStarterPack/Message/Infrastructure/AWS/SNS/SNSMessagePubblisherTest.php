<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Message\Infrastructure\AWS\SNS;

use DateTimeImmutable;
use DateTimeInterface;
use DDDStarterPack\Message\Application\Factory\MessageConsumerFactory;
use DDDStarterPack\Message\Application\Factory\MessageProducerFactory;
use DDDStarterPack\Message\Application\MessageConsumer;
use DDDStarterPack\Message\Infrastructure\AWS\AWSMessage;
use DDDStarterPack\Message\Infrastructure\AWS\RawClient\SnsRawClient;
use DDDStarterPack\Message\Infrastructure\AWS\RawClient\SqsRawClient;
use DDDStarterPack\Message\Infrastructure\AWS\SNS\Configuration\SNSConfigurationBuilder;
use DDDStarterPack\Message\Infrastructure\AWS\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Util\EnvVarUtil;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SNSMessagePubblisherTest extends TestCase
{
    use SqsRawClient;
    use SnsRawClient;

    private DateTimeImmutable $occurredAt;
    private MessageConsumer $messageConsumer;

    protected function setUp(): void
    {
        $this->setSnsTopicArn(EnvVarUtil::get('AWS_SNS_TOPIC_ARN'));
        $this->setQueueUrl(EnvVarUtil::get('AWS_SQS_QUEUE_NAME'));
        $this->purgeSqsQueue();

        $this->occurredAt = new DateTimeImmutable();

        $SQSconfiguration = SQSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->withQueueUrl($this->getQueueUrl())
            ->build();

        $this->messageConsumer = MessageConsumerFactory::create()->obtainConsumer($SQSconfiguration);
    }

    protected function tearDown(): void
    {
        $this->purgeSqsQueue();
    }

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function it_should_publish_message_on_topic(): void
    {
        $configuration = SNSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->withTopicArn($this->getSnsTopicArn())
            ->build();

        $snsMessagePublisher = MessageProducerFactory::create()->obtainProducer($configuration);

        $message = new AWSMessage(
            body: json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ]),
            occurredAt: $this->occurredAt,
            extra: [
                'MessageGroupId' => Uuid::uuid4()->toString(),
                'MessageDeduplicationId' => Uuid::uuid4()->toString(),
                'MessageAttributes' => [
                    'Evento' => [
                        'DataType' => 'String',
                        'StringValue' => 'EventoAvvenuto',
                    ],
                ],
            ]
        );

        $response = $snsMessagePublisher->send($message);

        self::assertTrue($response->isSuccess());
        self::assertEquals(1, $response->sentMessages());

        sleep(3);

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);

        $receiptHandle = $message->id();
        self::assertNotNull($receiptHandle);

        $this->deleteMessage($receiptHandle);
    }

    /**
     * @test
     */
    public function it_should_publish_message_on_topic_without_implied_arn(): void
    {
        $configuration = SNSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->build();

        $factory = MessageProducerFactory::create();
        $messageProducer = $factory->obtainProducer($configuration);

        $message = new AWSMessage(
            body: json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ]),
            occurredAt: $this->occurredAt,
            type: 'EventoAvvenuto',
            extra: [
                'TopicArn' => $this->getSnsTopicArn(),
                'MessageGroupId' => Uuid::uuid4()->toString(),
                'MessageDeduplicationId' => Uuid::uuid4()->toString(),
                'MessageAttributes' => [
                    'Evento' => [
                        'DataType' => 'String',
                        'StringValue' => 'EventoAvvenuto',
                    ],
                ],
            ]
        );

        $response = $messageProducer->send($message);

        self::assertTrue($response->isSuccess());
        self::assertEquals(1, $response->sentMessages());

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);

        $receiptHandle = $message->id();
        self::assertNotNull($receiptHandle);

        $this->deleteMessage($receiptHandle);
    }
}
