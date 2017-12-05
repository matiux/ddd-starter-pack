<?php

namespace Tests\DDDStarterPack\Domain\Notification\InMemory;

use DDDStarterPack\Domain\Model\Event\StoredDomainEvent;
use DDDStarterPack\Domain\Model\Message\BasicPublishedMessage;
use DDDStarterPack\Domain\Model\Message\PublishedMessageTracker;
use ReflectionObject;

class InMemoryPublishedMessageTracker implements PublishedMessageTracker
{
    private $publishedMessages = [];

    /**
     * Ritorna l'ID dell'ultimo PublishedMessage
     * Questo repository contine un solo record per exchangeName, che rappresenta l'ultimo evento pubblicato
     *
     * @param string $exchangeName
     * @return int|null
     */
    public function mostRecentPublishedMessageId(string $exchangeName): ?int
    {
        if (empty($this->publishedMessages)) {

            return null;
        }

        $messageTracked = array_filter($this->publishedMessages, function (BasicPublishedMessage $publishedMessage) use ($exchangeName) {

            $r = new ReflectionObject($publishedMessage);
            $p = $r->getProperty('exchangeName');
            $p->setAccessible(true);

            $check = $p->getValue($publishedMessage) == $exchangeName;

            return $check;
        });

        $messageTracked = reset($messageTracked);

        if (!$messageTracked) {

            return null;
        }

        return $messageTracked->mostRecentPublishedMessageId();
    }

    /**
     * E' responsabile di tracciare quale messaggio è stato spedito per ultimo
     * così teniamo traccia dell'ultimo evento pubblicato, e quando sarà necessario
     * pubblicare nuovi eventi, partiremo dall'ultimo salvato (escluso ovviamente),
     * o magari può essere comodo nel caso in cui sia necessario ripubblicarlo
     *
     * @param string $exchangeName
     * @param StoredDomainEvent $notification
     * @return null
     */
    public function trackMostRecentPublishedMessage(string $exchangeName, StoredDomainEvent $notification)
    {
        $maxId = $notification->eventId();

        $publishedMessage = array_filter($this->publishedMessages, function (BasicPublishedMessage $publishedMessage) use ($exchangeName) {

            $r = new ReflectionObject($publishedMessage);
            $p = $r->getProperty('exchangeName');
            $p->setAccessible(true);

            $check = $p->getValue($publishedMessage) == $exchangeName;

            return $check;

        });

        if (empty($publishedMessage)) {

            $publishedMessage = new BasicPublishedMessage(
                $exchangeName,
                $maxId
            );

        } else {

            $publishedMessage = reset($publishedMessage);
        }

        $publishedMessage->updateMostRecentPublishedMessageId($maxId);

        $this->publishedMessages[] = $publishedMessage;
    }
}
