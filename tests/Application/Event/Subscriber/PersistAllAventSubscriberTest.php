<?php

namespace Tests\DddStarterPack\Application\Event\Subscriber;

use DddStarterPack\Application\Event\Subscriber\PersistAllEventSubscriber;
use DddStarterPack\Domain\Model\Event\EventStore;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\DddStarterPack\Fake\Domain\Model\Event\FakeEvent;
use Tests\DddStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory\InMemoryEventStore;
use Tests\DddStarterPack\Fake\Infrastructure\Domain\Model\Event\InMemory\InMemoryStoredEventFactory;
use Tests\DddStarterPack\Fake\Infrastructure\Serializer\FakeEventSerializer;

class PersistAllAventSubscriberTest extends TestCase
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var PersistAllEventSubscriber
     */
    private $subscriber;

    protected function setUp()
    {
        parent::setUp();

        $this->eventStore = new InMemoryEventStore();

        $this->subscriber = new PersistAllEventSubscriber(
            $this->eventStore,
            new FakeEventSerializer(),
            new InMemoryStoredEventFactory($this->eventStore)
        );
    }

    /**
     * @test
     */
    public function it_should_store_thow_events()
    {
        $this->subscriber->handle(new FakeEvent(Uuid::uuid4()));
        $this->subscriber->handle(new FakeEvent(Uuid::uuid4()));

        $events = $this->eventStore->allStoredEventsSince(1);

        $this->assertCount(2, $events);
    }

    public function it_should_be_subscribed_to_all_events()
    {
        $this->assertTrue($this->subscriber->isSubscribedTo(new FakeEvent(Uuid::uuid4())));
    }
}
