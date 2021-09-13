<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\AWS\SQS;

use DateTimeImmutable;
use DateTimeInterface;
use DDDStarterPack\Application\Message\Factory\MessageConsumerFactory;
use DDDStarterPack\Application\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Application\Message\MessageConsumer;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Application\Util\EnvVarUtil;
use DDDStarterPack\Infrastructure\Application\Message\AWS\AWSMessage;
use DDDStarterPack\Infrastructure\Application\Message\AWS\RawClient\SnsRawClient;
use DDDStarterPack\Infrastructure\Application\Message\AWS\RawClient\SqsRawClient;
use DDDStarterPack\Infrastructure\Application\Message\AWS\SQS\Configuration\SQSConfigurationBuilder;
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

        $this->assertInstanceOf(AWSMessage::class, $message);

        $this->assertEquals(
            json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ]),
            $message->body()
        );
        $this->assertEquals('MyType', $message->type());
        $this->assertEquals(
            $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            $message->occurredAt()->format(DateTimeInterface::RFC3339_EXTENDED)
        );

        $id = $message->id();
        assert:self::assertNotNull($id);

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

        $this->assertInstanceOf(AWSMessage::class, $message);

        $messageId = $message->id();

        self::assertNotNull($messageId);

        $this->messageConsumer->delete($messageId);

        $message = $this->messageConsumer->consume();

        $this->assertNull($message);
    }
}
