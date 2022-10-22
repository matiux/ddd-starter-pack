<?php

declare(strict_types=1);

namespace Tests\Learning\SQS;

use DateTimeImmutable;
use DateTimeInterface;
use DDDStarterPack\Message\Infrastructure\Driver\AWS\RawClient\SqsRawClient;
use DDDStarterPack\Tool\EnvVarUtil;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SqsLearningTest extends TestCase
{
    use SqsRawClient;

    private DateTimeImmutable $occurredAt;

    protected function setUp(): void
    {
        $this->setQueueUrl(EnvVarUtil::get('AWS_SQS_QUEUE_NAME'));
        $this->purgeSqsQueue();

        $this->occurredAt = new DateTimeImmutable();
    }

    protected function tearDown(): void
    {
        $this->purgeSqsQueue();
    }

    /**
     * @test
     * @group learning
     * @group sqs
     */
    public function send_message_in_queue(): void
    {
        $msg = json_encode([
            'Foo' => 'Bar',
            'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
        ]);

        $result = $this->getSqsClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => $msg,
            'MessageGroupId' => Uuid::uuid4()->toString(),
            'MessageDeduplicationId' => Uuid::uuid4()->toString(),
        ]);

        $this->assertEquals(md5($msg), $result['MD5OfMessageBody']);

        self::assertNotEmpty($result['@metadata']);
        self::assertTrue(isset($result['@metadata']));
        self::assertIsArray($result['@metadata']);
        self::assertArrayHasKey('statusCode', $result['@metadata']);
        self::assertSame(200, $result['@metadata']['statusCode']);
    }

    /**
     * @test
     * @group learning
     * @group sqs
     */
    public function invia_messaggio_in_coda_con_attributo(): void
    {
        $myType = [
            'DataType' => 'String',
            'StringValue' => 'MyType',
        ];

        $this->getSqsClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => json_encode([
                'Foo' => 'Bar',
                'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
            ]),
            'MessageGroupId' => Uuid::uuid4()->toString(),
            'MessageDeduplicationId' => Uuid::uuid4()->toString(),
            'MessageAttributes' => [
                'Type' => $myType,
            ],
        ]);

        $result = $this->getSqsClient()->receiveMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'AttributeNames' => ['All'],
            'MessageAttributeNames' => ['All'],
        ]);

        self::assertNotEmpty($result['Messages']);
        self::assertIsArray($result['Messages']);
        self::assertCount(1, $result['Messages']);
        self::assertNotEmpty($result['Messages'][0]);
        self::assertIsArray($result['Messages'][0]);

        $message = $result['Messages'][0];

        self::assertArrayHasKey('MessageAttributes', $message);
        self::assertIsArray($message['MessageAttributes']);
        self::assertNotEmpty($message['MessageAttributes']);
        self::assertEquals($myType, $message['MessageAttributes']['Type']);
        self::assertArrayHasKey('ReceiptHandle', $message);
        self::assertIsString($message['ReceiptHandle']);

        $this->deleteMessage($message['ReceiptHandle']);
    }

    /**
     * @test
     * @group learning
     * @group sqs
     */
    public function ricevi_messaggio_dalla_coda(): void
    {
        $msg = json_encode([
            'Foo' => 'Bar',
            'occurredAt' => $this->occurredAt->format(DateTimeInterface::RFC3339_EXTENDED),
        ]);

        $this->getSqsClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => $msg,
            'MessageGroupId' => Uuid::uuid4()->toString(),
            'MessageDeduplicationId' => Uuid::uuid4()->toString(),
        ]);

        $result = $this->getSqsClient()->receiveMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'AttributeNames' => ['ApproximateReceiveCount'],
        ]);

        self::assertNotEmpty($result['Messages']);
        self::assertIsArray($result['Messages']);
        self::assertCount(1, $result['Messages']);
        self::assertNotEmpty($result['Messages'][0]);
        self::assertIsArray($result['Messages'][0]);

        $message = $result['Messages'][0];

        self::assertEquals(md5($msg), $message['MD5OfBody']);
        self::assertArrayHasKey('Attributes', $message);
        self::assertIsArray($message['Attributes']);
        self::assertArrayHasKey('ApproximateReceiveCount', $message['Attributes']);
        self::assertEquals(1, $message['Attributes']['ApproximateReceiveCount']);
        self::assertEquals($msg, $message['Body']);
        self::assertArrayHasKey('ReceiptHandle', $message);
        self::assertIsString($message['ReceiptHandle']);

        $this->deleteMessage($message['ReceiptHandle']);
    }
}
