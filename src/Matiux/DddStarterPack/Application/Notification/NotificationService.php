<?php

namespace DddStarterPack\Application\Notification;

use DddStarterPack\Domain\Event\EventStore;
use DddStarterPack\Domain\Model\Event\StoredEvent;
use JMS\Serializer\SerializerBuilder;

class NotificationService
{
    private $eventStore;
    private $publishedMessageTracker;
    private $messageProducer;
    private $serializer;

    public function __construct(EventStore $eventStore, PublishedMessageTracker $publishedMessageTracker, MessageProducer $messageProducer)
    {
        $this->eventStore = $eventStore;
        $this->publishedMessageTracker = $publishedMessageTracker;
        $this->messageProducer = $messageProducer;
    }

    public function publishNotifications($exchangeName)
    {
        /**
         * $notifications contiene tutti gli eventi non ancora pubblicati presi da EventStore,
         * partendo dall'id dell'ultimo evento pubblicato (quello più recente per intenderci)
         */
        $notifications = $this->listUnpublishedNotifications(
            $this->publishedMessageTracker->mostRecentPublishedMessageId($exchangeName)
        );

        if (!$notifications) {

            return 0;
        }

        $this->messageProducer->open($exchangeName);

        try {

            $publishedMessages = 0;
            $lastPublishedNotification = null;

            foreach ($notifications as $notification) {

                $lastPublishedNotification = $this->publish(
                    $exchangeName,
                    $notification,
                    $this->messageProducer
                );

                $publishedMessages++;
            }

        } catch (\Exception $e) {

            throw $e;
        }

        /**
         * Salvo l'ultimo evento pubblicato
         */
        $this->publishedMessageTracker->trackMostRecentPublishedMessage($exchangeName, $lastPublishedNotification);

        $this->messageProducer->close($exchangeName);

        /**
         * Ritorno il numero di messaggi pubblicati
         */
        return $publishedMessages;
    }

    private function listUnpublishedNotifications($mostRecentPublishedMessageId)
    {
        return $this->eventStore->allStoredEventsSince($mostRecentPublishedMessageId);
    }

    private function publish($exchangeName, StoredEvent $notification, MessageProducer $messageProducer)
    {
        $serialized = $this->serializer()->serialize($notification, 'json');

        $messageProducer->send(
            $exchangeName,
            $serialized,
            $notification->typeName(),
            $notification->eventId(),
            $notification->occurredOn()
        );

        return $notification;
    }

    private function serializer()
    {
        if (null === $this->serializer) {

            $this->serializer = SerializerBuilder::create()->build();
        }

        return $this->serializer;
    }
}
