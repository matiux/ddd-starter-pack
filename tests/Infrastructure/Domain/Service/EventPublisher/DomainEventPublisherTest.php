<?php

namespace Tests\DDDStarterPack\Domain\Service\EventPublisher;

use DDDStarterPack\Domain\Event\Publisher\DomainEventPublisher;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\DDDStarterPack\Fake\Domain\Model\Event\FakeDomainEvent;

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

        $subscriber = \Mockery::mock('DDDStarterPack\Domain\Event\Subscriber\EventSubscriber');
        $subscriber->shouldReceive('isSubscribedTo')
            ->with(\Mockery::type('DDDStarterPack\Domain\Model\Event\DomainEvent'))
            ->once()
            ->andReturn(true);

        $subscriber->shouldReceive('handle')
            ->with(\Mockery::type('DDDStarterPack\Domain\Model\Event\DomainEvent'))
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
