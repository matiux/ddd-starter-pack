<?php

namespace Tests\DddStarterPack\Application\Notification\InMemory;

use DddStarterPack\Application\Notification\PublishedMessageTracker;
use DddStarterPack\Domain\Model\Event\PublishedMessage;
use DddStarterPack\Domain\Model\Event\StoredEvent;

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

        array_walk($this->publishedMessages, function (PublishedMessage $publishedMessage) {

            $reflectionPublishedMessage = new \ReflectionClass($publishedMessage);


        });
    }

    /**
     * E' responsabile di tracciare quale messaggio è stato spedito per ultimo
     * così teniamo traccia dell'ultimo evento pubblicato, e quando sarà necessario
     * pubblicare nuovi eventi, partiremo dall'ultimo salvato (escluso ovviamente),
     * o magari può essere comodo nel caso in cui sia necessario ripubblicarlo
     *
     * @param string $exchangeName
     * @param StoredEvent $notification
     * @return null
     */
    public function trackMostRecentPublishedMessage(string $exchangeName, StoredEvent $notification)
    {
        $maxId = $notification->eventId();

        /**
         * TODO
         * finire la versione IN MEMORY
         */

        $maxId = $notification->eventId();

        $publishedMessage = $this->findOneByExchangeName($exchangeName);

        if (null === $publishedMessage) {

            $publishedMessage = new PublishedMessage(
                $exchangeName,
                $maxId
            );
        }

        $publishedMessage->updateMostRecentPublishedMessageId($maxId);

        $this->getEntityManager()->persist($publishedMessage);
    }
}
