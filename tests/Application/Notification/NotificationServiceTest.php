<?php

namespace Tests\DddStarterPack\Application\Notification;

use DddStarterPack\Application\Event\Subscriber\PersistAllEventSubscriber;
use DddStarterPack\Application\Notification\NotificationService;
use DddStarterPack\Application\Notification\PublishedMessageTracker;
use DddStarterPack\Domain\Model\Event\EventStore;
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

    protected function setUp()
    {
        $event01 = new FakeEvent(Uuid::uuid4());
        $event02 = new FakeEvent(Uuid::uuid4());

        $this->eventStore = new InMemoryEventStore();
        $storedEventFactory = new InMemoryStoredEventFactory($this->eventStore);

        $storedEvent01 = $storedEventFactory->build(get_class($event01), $event01->occurredOn(), (new FakeEventSerializer())->serialize($event01, 'json'));
        $storedEvent02 = $storedEventFactory->build(get_class($event01), $event01->occurredOn(), (new FakeEventSerializer())->serialize($event02, 'json'));

        $this->publishedMessageTracker = new InMemoryPublishedMessageTracker();

        $this->eventStore->append($storedEvent01);
        $this->eventStore->append($storedEvent02);
    }

    /**
     * @test
     */
    public function it_should_publish_a_message()
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

        $notificationService = new NotificationService(
            $this->eventStore,
            $this->publishedMessageTracker,
            $messageProducer
        );

        $published = $notificationService->publishNotifications('channel');

        $this->assertEquals(1, $published);
    }
}
