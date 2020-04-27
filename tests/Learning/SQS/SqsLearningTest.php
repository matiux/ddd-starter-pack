<?php

namespace Tests\Learning\SQS;

use Aws\Result;
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
    public function send_message_in_queue()
    {
        $msg = 'An awesome message!';

        $result = $this->getClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => 'An awesome message!',
        ]);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals(md5($msg), $result['MD5OfMessageBody']);
        $this->assertEquals(200, $result['@metadata']['statusCode']);

        $this->purgeSqsQueue();
    }

    /**
     * @test
     * @group learning
     * @group sqs
     */
    public function invia_messaggio_in_coda_con_attributo()
    {
        $myType = [
            'DataType' => 'String',
            'StringValue' => 'MyType'
        ];

        $this->getClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => 'An awesome message!',
            'MessageAttributes' => [
                'Type' => $myType
            ]
        ]);

        $result = $this->getClient()->receiveMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'AttributeNames' => ['ApproximateReceiveCount'],
            'MessageAttributeNames' => ['Type'],
        ]);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertCount(1, $result['Messages']);

        $message = $result['Messages'][0];

        $this->assertEquals($myType, $message['MessageAttributes']['Type']);

        $this->deleteMessage($message['ReceiptHandle']);
    }

    /**
     * @test
     * @group learning
     * @group sqs
     */
    public function ricevi_messaggio_dalla_coda()
    {
        $msg = 'An awesome message!';

        $this->getClient()->sendMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'MessageBody' => $msg,
        ]);

        $result = $this->getClient()->receiveMessage([
            'QueueUrl' => $this->getQueueUrl(),
            'AttributeNames' => ['ApproximateReceiveCount']
        ]);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertCount(1, $result['Messages']);

        $message = $result['Messages'][0];

        $this->assertEquals(md5($msg), $message['MD5OfBody']);
        $this->assertEquals(1, $message['Attributes']['ApproximateReceiveCount']);
        $this->assertEquals($msg, $message['Body']);

        $this->deleteMessage($message['ReceiptHandle']);
    }
}
