<?php

namespace Tests\DddStarterPack\Application\Event\Publisher;

use DddStarterPack\Application\Event\Publisher\EventPublisher;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\DddStarterPack\Fake\Domain\Model\Event\FakeEvent;

class EventPublisherTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_be_instantiable_by_static_method()
    {
        $eventPublisher = EventPublisher::instance();

        $this->assertInstanceOf(EventPublisher::class, $eventPublisher);
    }

    /**
     * @test
     */
    public function it_should_not_accept_two_equal_event_subscriber()
    {
        $eventPublisher = EventPublisher::instance();

        $subscriber = \Mockery::mock('DddStarterPack\Application\Event\Subscriber\EventSubscriber');
        $subscriber->shouldReceive('isSubscribedTo')
            ->with(\Mockery::type('DddStarterPack\Domain\Model\Event\Event'))
            ->once()
            ->andReturn(true);

        $subscriber->shouldReceive('handle')
            ->with(\Mockery::type('DddStarterPack\Domain\Model\Event\Event'))
            ->once();

        $eventPublisher->subscribe($subscriber);
        $eventPublisher->subscribe($subscriber);

        $eventPublisher->publish(new FakeEvent(Uuid::uuid4()));

        $this->assertNotNull($eventPublisher);
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Clone is not supported
     */
    public function it_should_not_be_cloned()
    {
        $eventPublisher = EventPublisher::instance();

        $clone = clone $eventPublisher;
    }
}
