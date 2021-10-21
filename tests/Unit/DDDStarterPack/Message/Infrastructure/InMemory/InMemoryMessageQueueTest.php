<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Infrastructure\InMemory;

use DateTimeImmutable;
use DDDStarterPack\Message\Infrastructure\InMemory\InMemoryMessage;
use DDDStarterPack\Message\Infrastructure\InMemory\InMemoryMessageConsumer;
use DDDStarterPack\Message\Infrastructure\InMemory\InMemoryMessageFactory;
use DDDStarterPack\Message\Infrastructure\InMemory\InMemoryMessageProducer;
use DDDStarterPack\Message\Infrastructure\InMemory\InMemoryMessageQueue;
use PHPUnit\Framework\TestCase;

class InMemoryMessageQueueTest extends TestCase
{
    private InMemoryMessageQueue $queue;
    private InMemoryMessageProducer $producer;
    private InMemoryMessageConsumer $consumer;

    protected function setUp(): void
    {
        $this->queue = new InMemoryMessageQueue();
        $this->producer = new InMemoryMessageProducer($this->queue);
        $this->consumer = new InMemoryMessageConsumer($this->queue);
    }

    /**
     * @test
     */
    public function it_should_handle_in_memory_message(): void
    {
        $messageBody = [
            'foo' => 'bar',
        ];
        $event = json_encode($messageBody);

        $occurredAt = new DateTimeImmutable();

        $message = (new InMemoryMessageFactory())->build(
            body: $event,
            occurredAt: $occurredAt,
            type: 'MyType',
            id: '123'
        );

        $response = $this->producer->send($message);
        self::assertSame(1, $response->sentMessages());
        self::assertTrue($response->isSuccess());
        self::assertSame([], $response->originalResponse());
        self::assertSame(['success' => true], $response->body());
        self::assertSame('123', $response->sentMessageId());

        self::assertSame(1, $this->queue->count());

        $consumedMessage = $this->consumer->consume();
        self::assertInstanceOf(InMemoryMessage::class, $consumedMessage);
        self::assertSame('MyType', $consumedMessage->type());
        self::assertSame($occurredAt, $consumedMessage->occurredAt());
        self::assertSame('123', $consumedMessage->id());

        self::assertInstanceOf(InMemoryMessage::class, $consumedMessage);
        $body = json_decode($consumedMessage->body(), true);
        self::assertSame($messageBody, $body);
    }
}
