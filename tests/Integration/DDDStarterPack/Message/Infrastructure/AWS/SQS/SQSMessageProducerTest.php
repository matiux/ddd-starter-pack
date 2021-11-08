<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Message\Infrastructure\AWS\SQS;

use Aws\Result;
use DateTimeImmutable;
use DateTimeInterface;
use DDDStarterPack\Message\Application\Configuration\Configuration;
use DDDStarterPack\Message\Application\Exception\InvalidConfigurationException;
use DDDStarterPack\Message\Application\Factory\MessageConsumerFactory;
use DDDStarterPack\Message\Application\Factory\MessageProducerFactory;
use DDDStarterPack\Message\Application\MessageConsumer;
use DDDStarterPack\Message\Infrastructure\AWS\AWSMessage;
use DDDStarterPack\Message\Infrastructure\AWS\RawClient\SnsRawClient;
use DDDStarterPack\Message\Infrastructure\AWS\RawClient\SqsRawClient;
use DDDStarterPack\Message\Infrastructure\AWS\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Message\Infrastructure\AWS\SQS\SQSMessageProducer;
use DDDStarterPack\Util\EnvVarUtil;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SQSMessageProducerTest extends TestCase
{
    use SqsRawClient;
    use SnsRawClient;

    private DateTimeImmutable $occurredAt;
    private Configuration $SQSConfiguration;
    private MessageConsumer $messageConsumer;

    protected function setUp(): void
    {
        $this->setQueueUrl(EnvVarUtil::get('AWS_SQS_QUEUE_NAME'));
        $this->purgeSqsQueue();

        $this->occurredAt = new DateTimeImmutable();

        $this->SQSConfiguration = SQSConfigurationBuilder::create()
            ->withRegion(EnvVarUtil::get('AWS_DEFAULT_REGION'))
            ->withQueueUrl($this->getQueueUrl())
            ->build();

        $this->messageConsumer = MessageConsumerFactory::create()->obtainConsumer($this->SQSConfiguration);
    }

    protected function tearDown(): void
    {
        $this->purgeSqsQueue();
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_driver_name_is_invalid(): void
    {
        self::expectException(InvalidArgumentException::class);

        $configuration = Configuration::withParams('foo', []);

        $messageProducer = new SQSMessageProducer();
        self::assertSame('SQS', $messageProducer->name());
        $messageProducer->bootstrap($configuration);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_required_params_are_missing(): void
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('The required option "region" is missing.');

        $configuration = Configuration::withParams('SQS', []);

        (new SQSMessageProducer())->bootstrap($configuration);
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_required_params_are_invalid(): void
    {
        self::expectException(InvalidConfigurationException::class);
        self::expectExceptionMessage('Invalid region');

        $configuration = Configuration::withParams('SQS', ['region' => '']);

        (new SQSMessageProducer())->bootstrap($configuration);
    }

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_producer_can_send_message_in_queue(): void
    {
        $factory = MessageProducerFactory::create();
        $messageProducer = $factory->obtainProducer($this->SQSConfiguration);

        $message = new AWSMessage(
            body: json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ]),
            occurredAt: $this->occurredAt,
            id: Uuid::uuid4()->toString(),
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

        $response = $messageProducer->send($message);

        self::assertTrue($response->isSuccess());
        self::assertEquals(1, $response->sentMessages());
        self::assertInstanceOf(Result::class, $response->originalResponse());
        $body = $response->body();
        self::assertIsArray($body);
        self::assertCount(5, $body);
        self::assertTrue(Uuid::isValid((string) $response->sentMessageId()));

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);

        $receiptHandle = $message->id();
        self::assertNotNull($receiptHandle);

        $this->deleteMessage($receiptHandle);
    }

    /**
     * @test
     */
    public function it_should_send_message_on_topic_without_implied_queue_url(): void
    {
        $factory = MessageProducerFactory::create();
        $messageProducer = $factory->obtainProducer($this->SQSConfiguration);

        $message = new AWSMessage(
            body: json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ]),
            occurredAt: $this->occurredAt,
            extra: [
                'MessageGroupId' => Uuid::uuid4()->toString(),
                'MessageDeduplicationId' => Uuid::uuid4()->toString(),
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
