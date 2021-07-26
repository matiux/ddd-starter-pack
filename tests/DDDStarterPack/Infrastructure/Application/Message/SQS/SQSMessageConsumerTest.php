<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Infrastructure\Application\Message\SQS;

use DateTimeImmutable;
use DDDStarterPack\Application\Message\Factory\MessageConsumerFactory;
use DDDStarterPack\Application\Message\Factory\MessageProducerFactory;
use DDDStarterPack\Application\Message\MessageConsumer;
use DDDStarterPack\Application\Message\MessageProducer;
use DDDStarterPack\Application\Util\EnvVarUtil;
use DDDStarterPack\Infrastructure\Application\Message\SQS\Configuration\SQSConfigurationBuilder;
use DDDStarterPack\Infrastructure\Application\Message\SQS\SQSMessage;
use PHPUnit\Framework\TestCase;
use Tests\Tool\SqsRawClient;

class SQSMessageConsumerTest extends TestCase
{
    use SqsRawClient;

    /** @var SQSMessage */
    private $SQSMessage;

    /** @var MessageConsumer */
    private $messageConsumer;

    /** @var MessageProducer */
    private $messageProducer;

    /** @var DateTimeImmutable */
    private $occurredAt;

    protected function setUp(): void
    {
        parent::setUp();

        $configuration = SQSConfigurationBuilder::create()
            ->withRegion(EnvVarUtil::get('AWS_DEFAULT_REGION'))
            ->withQueue($this->getQueueUrl())
            ->build();

        $this->messageConsumer = MessageConsumerFactory::create()->obtainConsumer($configuration);
        $this->messageProducer = MessageProducerFactory::create()->obtainProducer($configuration);

        $this->occurredAt = new DateTimeImmutable();
        $this->SQSMessage = new SQSMessage('Body', $this->occurredAt, 'MyType', '28');
    }

    /**
     * @test
     * @group sqs
     * @group producer
     */
    public function message_consumer_can_receive_message(): void
    {
        $this->messageProducer->send($this->SQSMessage);

        $message = $this->messageConsumer->consume();

        $this->assertInstanceOf(SQSMessage::class, $message);

        $this->assertEquals('Body', $message->body());
        $this->assertEquals('MyType', $message->type());
        $this->assertEquals($this->occurredAt->format('Y-m-d H:i:s'), $message->occurredAt()->format('Y-m-d H:i:s'));

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
        $this->messageProducer->send($this->SQSMessage);

        $message = $this->messageConsumer->consume();

        $this->assertInstanceOf(SQSMessage::class, $message);

        $messageId = $message->id();

        self::assertNotNull($messageId);

        $this->messageConsumer->delete($messageId);

        $message = $this->messageConsumer->consume();

        $this->assertNull($message);
    }
}
