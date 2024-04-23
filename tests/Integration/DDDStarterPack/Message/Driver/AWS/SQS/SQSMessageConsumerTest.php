<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Message\Driver\AWS\SQS;

use DDDStarterPack\Message\Driver\AWS\AWSMessage;
use DDDStarterPack\Message\Driver\AWS\RawClient\SnsRawClient;
use DDDStarterPack\Message\Driver\AWS\RawClient\SqsRawClient;
use DDDStarterPack\Message\Driver\AWS\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Message\Factory\MessageConsumerFactory;
use DDDStarterPack\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Message\MessageConsumer;
use DDDStarterPack\Message\MessageProducer;
use DDDStarterPack\Tool\EnvVarUtil;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SQSMessageConsumerTest extends TestCase
{
    use SqsRawClient;
    use SnsRawClient;

    private AWSMessage $message;
    private AWSMessage $message2;
    private MessageConsumer $messageConsumer;
    private MessageProducer $messageProducer;
    private \DateTimeImmutable $occurredAt;

    public function setUp(): void
    {
        $this->setQueueUrl(EnvVarUtil::get('AWS_SQS_QUEUE_NAME'));
        $this->purgeSqsQueue();

        $configuration = SQSConfigurationBuilder::create()
            ->withRegion(EnvVarUtil::get('AWS_DEFAULT_REGION'))
            ->withQueueUrl($this->getQueueUrl())
            ->build();

        $this->messageConsumer = MessageConsumerFactory::create()->obtainConsumer($configuration);
        $this->messageProducer = MessageProducerFactory::create()->obtainProducer($configuration);

        $this->occurredAt = new \DateTimeImmutable();
        $this->message = new AWSMessage(
            body: json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(\DateTimeInterface::RFC3339_EXTENDED),
            ]),
            occurredAt: $this->occurredAt,
            type: 'MyType',
            id: Uuid::uuid4()->toString(),
            extra: [
                'MessageGroupId' => Uuid::uuid4()->toString(),
                'MessageDeduplicationId' => Uuid::uuid4()->toString(),
            ],
        );

        $this->message2 = new AWSMessage(
            body: json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(\DateTimeInterface::RFC3339_EXTENDED),
            ]),
            occurredAt: null,
            type: null,
            id: Uuid::uuid4()->toString(),
            extra: [
                'MessageGroupId' => Uuid::uuid4()->toString(),
                'MessageDeduplicationId' => Uuid::uuid4()->toString(),
            ],
        );
    }

    public function tearDown(): void
    {
        $this->purgeSqsQueue();
    }

    /**
     * @test
     *
     * @group sqs
     * @group consumer
     */
    public function message_consumer_can_receive_message(): void
    {
        $this->messageProducer->send($this->message);

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);

        self::assertEquals(
            json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(\DateTimeInterface::RFC3339_EXTENDED),
            ]),
            $message->body(),
        );
        self::assertEquals('MyType', $message->type());
        $occurredAt = $message->occurredAt();
        self::assertNotNull($occurredAt);
        self::assertEquals(
            $this->occurredAt->format(\DateTimeInterface::RFC3339_EXTENDED),
            $occurredAt->format(\DateTimeInterface::RFC3339_EXTENDED),
        );

        $id = $message->id();
        self::assertNotNull($id);

        $this->deleteMessage($id);
    }

    /**
     * @test
     *
     * @group sqs
     * @group consumer
     */
    public function message_consumer_can_receive_message_without_message_attributes(): void
    {
        $this->messageProducer->send($this->message2);

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);

        self::assertEquals(
            json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(\DateTimeInterface::RFC3339_EXTENDED),
            ]),
            $message->body(),
        );
        self::assertNull($message->type());
        self::assertNull($message->occurredAt());

        $id = $message->id();
        self::assertNotNull($id);

        $this->deleteMessage($id);
    }

    /**
     * @test
     *
     * @group sqs
     * @group consumer
     */
    public function message_consumer_can_delete_message(): void
    {
        $this->messageProducer->send($this->message);

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);

        $messageId = $message->id();

        self::assertNotNull($messageId);

        $this->messageConsumer->delete($messageId);

        $message = $this->messageConsumer->consume();

        self::assertNull($message);
    }

    /**
     * @test
     *
     * @group sqs
     * @group consumer
     */
    public function message_consumer_can_receive_multiple_messages(): void
    {
        $this->messageProducer->sendBatch([$this->message, $this->message2]);

        $messages = $this->messageConsumer->consumeBatch(maxNumberOfMessages: 10);

        self::assertCount(2, $messages);
        self::assertContainsOnlyInstancesOf(AWSMessage::class, $messages);

        foreach ($messages as $message) {
            $this->deleteMessage((string) $message->id());
        }
    }

    /**
     * @test
     *
     * @group sqs
     * @group consumer
     */
    public function message_consumer_cannot_receive_more_than_10_messages_in_a_single_batch(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Exceed max batch size of 10 messages');

        $this->messageConsumer->consumeBatch(maxNumberOfMessages: 11);
    }
}
