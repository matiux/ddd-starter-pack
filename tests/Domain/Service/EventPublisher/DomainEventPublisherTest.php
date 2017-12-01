<?php

namespace Tests\DddStarterPack\Domain\Service\EventPublisher;

use DddStarterPack\Domain\Service\EventPublisher\DomainEventPublisher;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\DddStarterPack\Fake\Domain\Model\Event\FakeDomainEvent;

class DomainEventPublisherTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_be_instantiable_by_static_method()
    {
        $eventPublisher = DomainEventPublisher::instance();

        $this->assertInstanceOf(DomainEventPublisher::class, $eventPublisher);
    }

    /**
     * @test
     */
    public function it_should_not_accept_two_equal_event_subscriber()
    {
        $eventPublisher = DomainEventPublisher::instance();

        $subscriber = \Mockery::mock('DddStarterPack\Domain\Service\EventSubscriber\EventSubscriber');
        $subscriber->shouldReceive('isSubscribedTo')
            ->with(\Mockery::type('DddStarterPack\Domain\Model\Event\DomainEvent'))
            ->once()
            ->andReturn(true);

        $subscriber->shouldReceive('handle')
            ->with(\Mockery::type('DddStarterPack\Domain\Model\Event\DomainEvent'))
            ->once();

        $eventPublisher->subscribe($subscriber);
        $eventPublisher->subscribe($subscriber);

        $eventPublisher->publish(new FakeDomainEvent(Uuid::uuid4()));

        $this->assertNotNull($eventPublisher);
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Clone is not supported
     */
    public function it_should_not_be_cloned()
    {
        $eventPublisher = DomainEventPublisher::instance();

        $clone = clone $eventPublisher;
    }
}
