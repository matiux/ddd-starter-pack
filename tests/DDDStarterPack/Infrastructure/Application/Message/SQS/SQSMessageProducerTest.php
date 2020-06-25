<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessage;
use PHPUnit\Framework\TestCase;
use Tests\Tool\SqsRawClient;

/**
 * Class SQSMessageProducerTest.
 *
 * @psalm-suppress MissingConstructor
 */
class SQSMessageProducerTest extends TestCase
{
    use SqsRawClient;

    /** @var SQSMessage */
    private $SQSMessage;

    /** @var MessageProducer */
    private $messageProducer;

    /** @var DateTimeImmutable */
    private $occurredAt;

    protected function setUp(): void
    {
        parent::setUp();

        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->withQueue($this->getQueueUrl())
            ->build();

        $factory = MessageProducerFactory::create();
        $this->messageProducer = $factory->obtainProducer($configuration);

        $this->occurredAt = new DateTimeImmutable();

        $this->SQSMessage = new SQSMessage('Body', $this->occurredAt, 'MyType', '28');
    }

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_provider_can_send_message_in_queue(): void
    {
        $response = $this->messageProducer->send($this->SQSMessage);

        self::assertTrue($response->isSuccess());
        self::assertEquals(1, $response->sentMessages());

        $this->purgeSqsQueue();
    }

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_provider_can_send_message_settings_attributes(): void
    {
        $this->messageProducer->send($this->SQSMessage);

        $result = $this->getClient()->receiveMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'AttributeNames' => ['ApproximateReceiveCount'],
            'MessageAttributeNames' => ['Type', 'OccurredAt'],
        ]);

        self::assertNotEmpty($result['Messages']);
        self::assertIsArray($result['Messages']);
        self::assertCount(1, $result['Messages']);
        self::assertNotEmpty($result['Messages'][0]);
        self::assertIsArray($result['Messages'][0]);

        $message = $result['Messages'][0];

        $type = [
            'StringValue' => 'MyType',
            'DataType' => 'String',
        ];

        $occurredAt = [
            'StringValue' => $this->occurredAt->format('Y-m-d H:i:s'),
            'DataType' => 'String',
        ];

        self::assertArrayHasKey('MessageAttributes', $message);
        self::assertIsArray($message['MessageAttributes']);
        self::assertNotEmpty($message['MessageAttributes']);
        self::assertEquals($type, $message['MessageAttributes']['Type']);
        self::assertEquals($occurredAt, $message['MessageAttributes']['OccurredAt']);
        self::assertArrayHasKey('ReceiptHandle', $message);
        self::assertIsString($message['ReceiptHandle']);

        $this->deleteMessage($message['ReceiptHandle']);
    }
}
