<?php

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\Factory\MessageConsumerFactory;
use DDDStarterPack\Application\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessage;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageConsumer;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessageProducer;
use PHPUnit\Framework\TestCase;
use Tests\Tool\SqsRawClient;

class SQSMessageConsumerTest extends TestCase
{
    use SqsRawClient;

    /** @var SQSMessage */
    private $SQSMessage;

    /** @var SQSMessageConsumer */
    private $messageConsumer;

    /** @var SQSMessageProducer */
    private $messageProducer;

    /** @var DateTimeImmutable */
    private $occurredAt;

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_consumer_can_receive_message()
    {
        $this->messageProducer->send($this->SQSMessage);

        $message = $this->messageConsumer->consume();

        $this->assertInstanceOf(SQSMessage::class, $message);

        $this->assertEquals('Body', $message->body());
        $this->assertEquals('MyType', $message->type());
        $this->assertEquals($this->occurredAt->format('Y-m-d H:i:s'), $message->occurredAt()->format('Y-m-d H:i:s'));

        $this->deleteMessage($message->id());
    }

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_consumer_can_delete_message()
    {
        $this->messageProducer->send($this->SQSMessage);

        $message = $this->messageConsumer->consume();

        $this->assertInstanceOf(SQSMessage::class, $message);

        $this->messageConsumer->delete($message->id());

        $message = $this->messageConsumer->consume();

        $this->assertNull($message);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $configuration = SQSConfigurationBuilder::create()
            ->withRegion('eu-west-1')
            ->withQueue($this->getQueueUrl())
            ->build();

        $this->messageConsumer = MessageConsumerFactory::create()->obtainConsumer($configuration);
        $this->messageProducer = MessageProducerFactory::create()->obtainProducer($configuration);

        $this->occurredAt = new DateTimeImmutable();
        $this->SQSMessage = new SQSMessage('Body', $this->occurredAt, 'MyType', 28);
    }
}
