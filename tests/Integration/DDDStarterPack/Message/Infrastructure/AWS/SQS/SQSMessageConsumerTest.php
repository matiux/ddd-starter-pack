<?php

declare(strict_types=1);

namespace Tests\Integration\DDDStarterPack\Message\Infrastructure\AWS\SQS;

use DateTimeImmutable;
use DateTimeInterface;
use DDDStarterPack\Message\Application\Factory\MessageConsumerFactory;
use DDDStarterPack\Message\Application\Factory\MessageProducerFactory;
use DDDStarterPack\Message\Application\MessageConsumer;
use DDDStarterPack\Message\Application\MessageProducer;
use DDDStarterPack\Message\Infrastructure\AWS\AWSMessage;
use DDDStarterPack\Message\Infrastructure\AWS\RawClient\SnsRawClient;
use DDDStarterPack\Message\Infrastructure\AWS\RawClient\SqsRawClient;
use DDDStarterPack\Message\Infrastructure\AWS\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Util\EnvVarUtil;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SQSMessageConsumerTest extends TestCase
{
    use SqsRawClient;
    use SnsRawClient;

    private AWSMessage $message;
    private MessageConsumer $messageConsumer;
    private MessageProducer $messageProducer;
    private DateTimeImmutable $occurredAt;

    public function setUp(): void
    {
        parent::setUp();

        $this->setQueueUrl(EnvVarUtil::get('AWS_SQS_QUEUE_NAME'));
        $this->purgeSqsQueue();

        $configuration = SQSConfigurationBuilder::create()
            ->withRegion(EnvVarUtil::get('AWS_DEFAULT_REGION'))
            ->withQueueUrl($this->getQueueUrl())
            ->build();

        $this->messageConsumer = MessageConsumerFactory::create()->obtainConsumer($configuration);
        $this->messageProducer = MessageProducerFactory::create()->obtainProducer($configuration);

        $this->occurredAt = new DateTimeImmutable();
        $this->message = new AWSMessage(
            body: json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ]),
            occurredAt: $this->occurredAt,
            type: 'MyType',
            extra: [
                'MessageGroupId' => Uuid::uuid4()->toString(),
                'MessageDeduplicationId' => Uuid::uuid4()->toString(),
            ]
        );
    }

    public function tearDown(): void
    {
        $this->purgeSqsQueue();
    }

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_consumer_can_receive_message(): void
    {
        $this->messageProducer->send($this->message);

        $message = $this->messageConsumer->consume();

        self::assertInstanceOf(AWSMessage::class, $message);

        self::assertEquals(
            json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ]),
            $message->body()
        );
        self::assertEquals('MyType', $message->type());
        $occurredAt = $message->occurredAt();
        self::assertNotNull($occurredAt);
        self::assertEquals(
            $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            $occurredAt->format(DateTimeInterface::RFC3339_EXTENDED)
        );

        $id = $message->id();
        self::assertNotNull($id);

        $this->deleteMessage($id);
    }

    /**
     * @test
     * @group sqs
     * @group producer
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
}
