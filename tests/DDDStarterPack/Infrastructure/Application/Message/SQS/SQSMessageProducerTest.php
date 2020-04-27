<?php

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS;

use Aws\Result;
use DateTimeImmutable;
use DDDStarterPack\Application\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Application\Message\MessageProducerResponse;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessage;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageProducer;
use PHPUnit\Framework\TestCase;
use Tests\Tool\SqsRawClient;

class SQSMessageProducerTest extends TestCase
{
    use SqsRawClient;

    /** @var SQSMessage */
    private $SQSMessage;

    /** @var SQSMessageProducer */
    private $messageProducer;

    /** @var DateTimeImmutable */
    private $occurredAt;

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_provider_can_send_message_in_queue()
    {
        $response = $this->messageProducer->send($this->SQSMessage);

        $this->assertInstanceOf(MessageProducerResponse::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertEquals(1, $response->sentMessages());

        $this->purgeSqsQueue();
    }

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_provider_can_send_message_settings_attributes()
    {
        $this->messageProducer->send($this->SQSMessage);

        $result = $this->getClient()->receiveMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'AttributeNames' => ['ApproximateReceiveCount'],
            'MessageAttributeNames' => ['Type', 'OccurredAt'],
        ]);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertCount(1, $result['Messages']);

        $message = $result['Messages'][0];

        $type = [
            'StringValue' => 'MyType',
            'DataType' => 'String'
        ];

        $occurredAt = [
            'StringValue' => $this->occurredAt->format('Y-m-d H:i:s'),
            'DataType' => 'String'
        ];

        $this->assertEquals($type, $message['MessageAttributes']['Type']);
        $this->assertEquals($occurredAt, $message['MessageAttributes']['OccurredAt']);

        $this->deleteMessage($message['ReceiptHandle']);
    }

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

        $this->SQSMessage = new SQSMessage('Body', $this->occurredAt, 'MyType', 28);
    }
}
