<?php

namespace Tests\DDDStarterPack\Application\Notification;

use DDDStarterPack\Application\Notification\MessageProducer;
use DddStarterPack\Application\Notification\NotificationMessageFactory;
use DDDStarterPack\Application\Notification\NotificationService;
use DDDStarterPack\Domain\Model\Event\EventStore;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventInterface;
use DDDStarterPack\Domain\Model\Message\PublishedMessageTracker;
use DDDStarterPack\Infrastructure\Application\Notification\InMemory\InMemoryNotificationMessageFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\DDDStarterPack\Infrastructure\Domain\Model\Event\FakeDomainEvent;
use Tests\DDDStarterPack\Infrastructure\Domain\Model\Event\InMemoryEventStore;
use Tests\DDDStarterPack\Infrastructure\Domain\Model\Event\InMemoryStoredDomainEventFactory;
use Tests\DDDStarterPack\Infrastructure\Domain\Model\Message\InMemory\InMemoryPublishedMessageTracker;


class NotificationServiceTest extends TestCase
{
    /**
     * @var PublishedMessageTracker
     */
    private $publishedMessageTracker;

    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var StoredDomainEventInterface
     */
    private $storedEvent01;

    /**
     * @var MessageProducer
     */
    private $messageProducer;

    /**
     * @var NotificationMessageFactory
     */
    private $notificationMessageFactory;

    protected function setUp()
    {
        $this->messageProducer = $this->messageProducerMock();
        $this->notificationMessageFactory = new InMemoryNotificationMessageFactory();

        $event01 = new FakeDomainEvent(Uuid::uuid4());
        $event02 = new FakeDomainEvent(Uuid::uuid4());

        $this->eventStore = new InMemoryEventStore();
        $storedEventFactory = new InMemoryStoredDomainEventFactory($this->eventStore);
        $this->eventStore->setStoredDomainEventFactory($storedEventFactory);

        $this->storedEvent01 = $storedEventFactory->build(null, get_class($event01), $event01->occurredOn(), '');

        $this->eventStore->add($event01);
        $this->eventStore->add($event02);

        $this->publishedMessageTracker = new InMemoryPublishedMessageTracker();
    }

    /**
     * @test
     */
    public function it_should_publish_a_message()
    {
        $notificationService = new NotificationService(
            $this->eventStore,
            $this->publishedMessageTracker,
            $this->messageProducer,
            $this->notificationMessageFactory
        );

        $published = $notificationService->publishNotifications('channel');

        $this->assertEquals(2, $published);
    }

    /**
     * @test
     */
    public function it_should_publish_a_message_starting_from_last_published_event()
    {
        $this->publishedMessageTracker->trackMostRecentPublishedMessage('channel', $this->storedEvent01);

        $notificationService = new NotificationService(
            $this->eventStore,
            $this->publishedMessageTracker,
            $this->messageProducer,
            $this->notificationMessageFactory
        );

        $published = $notificationService->publishNotifications('channel');

        $this->assertEquals(1, $published);
    }

    private function messageProducerMock()
    {
        $messageProducer = \Mockery::mock('DDDStarterPack\Application\Notification\MessageProducer');
        $messageProducer->shouldReceive('open')
            ->with(\Mockery::type('string'));

        $messageProducer->shouldReceive('send')
            ->with(\Mockery::type('DDDStarterPack\Application\Notification\Message'));

        $messageProducer->shouldReceive('close');

        return $messageProducer;
    }
}
