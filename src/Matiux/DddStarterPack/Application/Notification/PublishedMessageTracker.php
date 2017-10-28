<?php

namespace DddStarterPack\Application\Notification;

use DddStarterPack\Domain\Model\Event\StoredEvent;

interface PublishedMessageTracker
{
    /**
     * Ritorna l'ID dell'ultimo PublishedMessage
     *
     * @param string $exchangeName
     * @return int|null
     */
    public function mostRecentPublishedMessageId(string $exchangeName);

    /**
     * E' responsabile di tracciare quale messaggio è stato spedito per ultimo
     * così seniamo traccia dell'ultimo evento pubblicato, e quando sarà necessario
     * pubblicare nuovi eventi, partiremo dall'ultimo salvato (escluso ovviamente),
     * o magari può essere comodo nel caso in cui sia necessario ripubblicarlo
     *
     * @param string $exchangeName
     * @param StoredEvent $notification
     * @return null
     */
    public function trackMostRecentPublishedMessage(string $exchangeName, StoredEvent $notification);
}
