<?php

namespace Tests\DDDStarterPack\Application\Notification;

use DDDStarterPack\Application\Notification\MessageProducer;
use DDDStarterPack\Application\Notification\NotificationService;
use DDDStarterPack\Domain\Model\Event\EventStore;
use DDDStarterPack\Domain\Model\Event\StoredDomainEvent;
use DDDStarterPack\Domain\Model\Message\PublishedMessageTracker;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\DDDStarterPack\Application\Notification\InMemory\InMemoryPublishedMessageTracker;
use Tests\DDDStarterPack\Fake\Domain\Model\Event\FakeDomainEvent;
use Tests\DDDStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory\InMemoryEventStore;
use Tests\DDDStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory\InMemoryStoredDomainEventFactory;
use Tests\DDDStarterPack\Fake\Infrastructure\Serializer\FakeEventSerializer;

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
     * @var StoredDomainEvent
     */
    private $storedEvent01;

    /**
     * @var MessageProducer
     */
    private $messageProducer;

    protected function setUp()
    {
        $this->messageProducer = $this->messageProducerMock();

        $this->eventStore = new InMemoryEventStore();

        $event01 = new FakeDomainEvent(Uuid::uuid4());
        $event02 = new FakeDomainEvent(Uuid::uuid4());

        $storedEventFactory = new InMemoryStoredDomainEventFactory($this->eventStore);

        $this->storedEvent01 = $storedEventFactory->build(get_class($event01), $event01->occurredOn(), (new FakeEventSerializer())->serialize($event01, 'json'));
        $this->eventStore->add($this->storedEvent01);

        $storedEvent02 = $storedEventFactory->build(get_class($event01), $event01->occurredOn(), (new FakeEventSerializer())->serialize($event02, 'json'));
        $this->eventStore->add($storedEvent02);

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
            $this->messageProducer
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
            $this->messageProducer
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
            ->with(
                \Mockery::type('string'),
                \Mockery::type('string'),
                \Mockery::type('string'),
                \Mockery::type('int'),
                \Mockery::type('\DateTimeImmutable')
            );

        $messageProducer->shouldReceive('close');

        return $messageProducer;
    }
}
