<?php

namespace Tests\DddStarterPack\Application\Notification;

use DddStarterPack\Application\Event\Subscriber\PersistAllEventSubscriber;
use DddStarterPack\Application\Notification\MessageProducer;
use DddStarterPack\Application\Notification\NotificationService;
use DddStarterPack\Application\Notification\PublishedMessageTracker;
use DddStarterPack\Domain\Model\Event\EventStore;
use DddStarterPack\Domain\Model\Event\StoredEvent;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\DddStarterPack\Application\Notification\InMemory\InMemoryPublishedMessageTracker;
use Tests\DddStarterPack\Fake\Domain\Model\Event\FakeEvent;
use Tests\DddStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory\InMemoryEventStore;
use Tests\DddStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory\InMemoryStoredEventFactory;
use Tests\DddStarterPack\Fake\Infrastructure\Serializer\FakeEventSerializer;

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
     * @var StoredEvent
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

        $event01 = new FakeEvent(Uuid::uuid4());
        $event02 = new FakeEvent(Uuid::uuid4());

        $storedEventFactory = new InMemoryStoredEventFactory($this->eventStore);

        $this->storedEvent01 = $storedEventFactory->build(get_class($event01), $event01->occurredOn(), (new FakeEventSerializer())->serialize($event01, 'json'));
        $this->eventStore->append($this->storedEvent01);

        $storedEvent02 = $storedEventFactory->build(get_class($event01), $event01->occurredOn(), (new FakeEventSerializer())->serialize($event02, 'json'));
        $this->eventStore->append($storedEvent02);

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
        $messageProducer = \Mockery::mock('DddStarterPack\Application\Notification\MessageProducer');
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
