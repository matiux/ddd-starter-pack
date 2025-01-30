<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Message\Driver\AWS\SQS;

use Aws\Result;
use DDDStarterPack\Message\Configuration\Configuration;
use DDDStarterPack\Message\Driver\AWS\AWSMessage;
use DDDStarterPack\Message\Driver\AWS\RawClient\SnsRawClient;
use DDDStarterPack\Message\Driver\AWS\RawClient\SqsRawClient;
use DDDStarterPack\Message\Driver\AWS\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Message\Driver\AWS\SQS\SQSMessageProducer;
use DDDStarterPack\Message\Exception\InvalidConfigurationException;
use DDDStarterPack\Message\Factory\MessageConsumerFactory;
use DDDStarterPack\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Message\MessageConsumer;
use DDDStarterPack\Tool\EnvVarUtil;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @psalm-suppress PossiblyFalseArgument
 */
class SQSMessageProducerTest extends TestCase
{
    use SqsRawClient;
    use SnsRawClient;

    private \DateTimeImmutable $occurredAt;
    private Configuration $SQSConfiguration;
    private MessageConsumer $messageConsumer;

    protected function setUp(): void
    {
        $this->setQueueUrl(EnvVarUtil::get('AWS_SQS_QUEUE_NAME'));
        $this->purgeSqsQueue();

        $this->occurredAt = new \DateTimeImmutable();

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
        self::expectException(\InvalidArgumentException::class);

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
     *
     * @group sqs
     * @group producer
     */
    public function it_should_send_message_in_queue(): void
    {
        $response = MessageProducerFactory::create()
            ->obtainProducer($this->SQSConfiguration)
            ->send($this->createMessage());

        self::assertTrue($response->isSuccess());
        self::assertEquals(1, $response->sentMessages());
        self::assertInstanceOf(Result::class, $response->originalResponse());

        self::assertIsArray($response->body());
        self::assertCount(5, (array) $response->body());
        self::assertTrue(Uuid::isValid((string) $response->sentMessageId()));

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);
        self::assertNotNull($message->id());

        $this->deleteMessage((string) $message->id());
    }

    /**
     * @test
     */
    public function it_should_send_message_in_queue_without_implied_queue_url(): void
    {
        $SQSConfiguration = SQSConfigurationBuilder::create()
            ->withRegion(EnvVarUtil::get('AWS_DEFAULT_REGION'))
            ->build();

        $response = MessageProducerFactory::create()
            ->obtainProducer($SQSConfiguration)
            ->send(
                $this->createMessage(
                    extra: ['QueueUrl' => $this->getQueueUrl()],
                ),
            );

        self::assertTrue($response->isSuccess());
        self::assertEquals(1, $response->sentMessages());

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);
        self::assertNotNull($message->id());

        $this->deleteMessage((string) $message->id());
    }

    /**
     * @test
     *
     * @group sqs
     * @group producer
     */
    public function it_should_send_message_batch_in_queue(): void
    {
        $response = MessageProducerFactory::create()
            ->obtainProducer($this->SQSConfiguration)
            ->sendBatch(
                (array) $this->createMessage(2),
            );

        self::assertTrue($response->isSuccess());
        self::assertEquals(2, $response->sentMessages());

        $body = $this->messageConsumer->consume()?->body();
        self::assertIsString($body);
        self::assertMatchesRegularExpression('/"Foo":"Bar 0"/', $body);

        $body = $this->messageConsumer->consume()?->body();
        self::assertIsString($body);
        self::assertMatchesRegularExpression('/"Foo":"Bar 1"/', $body);
    }

    private function createMessage(int $amount = 1, array $extra = []): array|AWSMessage
    {
        $result = [];

        for ($i = 0; $i < $amount; ++$i) {
            $result[] = new AWSMessage(
                body: json_encode([
                    'Foo' => sprintf('Bar %s', $i),
                    'occurredAt' => $this->occurredAt->format(\DateTimeInterface::RFC3339_EXTENDED),
                ]),
                occurredAt: $this->occurredAt,
                id: Uuid::uuid4()->toString(),
                extra: $extra + [
                    'MessageGroupId' => sprintf('%s-%s', Uuid::uuid4()->toString(), $i),
                    'MessageDeduplicationId' => sprintf('%s-%s', Uuid::uuid4()->toString(), $i),
                    'MessageAttributes' => [
                        'Evento' => [
                            'DataType' => 'String',
                            'StringValue' => 'EventoAvvenuto',
                        ],
                    ],
                ],
            );
        }

        return 1 === count($result) ? $result[0] : $result;
    }
}
