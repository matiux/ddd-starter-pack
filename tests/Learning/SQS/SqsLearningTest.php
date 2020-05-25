<?php

declare(strict_types=1);

namespace Tests\Learning\SQS;

use PHPUnit\Framework\TestCase;
use Tests\Tool\SqsRawClient;

class SqsLearningTest extends TestCase
{
    use SqsRawClient;

    /**
     * @test
     * @group learning
     * @group sqs
     */
    public function send_message_in_queue(): void
    {
        $msg = 'An awesome message!';

        $result = $this->getClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => 'An awesome message!',
        ]);

        $this->assertEquals(md5($msg), $result['MD5OfMessageBody']);

        self::assertNotEmpty($result['@metadata']);
        self::assertTrue(isset($result['@metadata']));
        self::assertIsArray($result['@metadata']);
        self::assertArrayHasKey('statusCode', $result['@metadata']);
        self::assertSame(200, $result['@metadata']['statusCode']);

        $this->purgeSqsQueue();
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

        $this->getClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => 'An awesome message!',
            'MessageAttributes' => [
                'Type' => $myType,
            ],
        ]);

        $result = $this->getClient()->receiveMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'AttributeNames' => ['ApproximateReceiveCount'],
            'MessageAttributeNames' => ['Type'],
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
        $msg = 'An awesome message!';

        $this->getClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => $msg,
        ]);

        $result = $this->getClient()->receiveMessage([
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
