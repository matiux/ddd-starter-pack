<?php

namespace DDDStarterPack\Domain\Model\Message;

use DDDStarterPack\Domain\Model\Event\BasicStoredDomainEvent;

interface PublishedMessageTracker
{
    /**
     * Ritorna l'ID dell'ultimo PublishedMessage
     * Questo repository contine un solo record per exchangeName, che rappresenta l'ultimo evento pubblicato
     *
     * @param string $exchangeName
     * @return int|null
     */
    public function mostRecentPublishedMessageId(string $exchangeName): ?int;

    /**
     * E' responsabile di tracciare quale messaggio è stato spedito per ultimo
     * così teniamo traccia dell'ultimo evento pubblicato, e quando sarà necessario
     * pubblicare nuovi eventi, partiremo dall'ultimo salvato (escluso ovviamente),
     * o magari può essere comodo nel caso in cui sia necessario ripubblicarlo
     *
     * @param string $exchangeName
     * @param BasicStoredDomainEvent $notification
     * @return null
     */
    public function trackMostRecentPublishedMessage(string $exchangeName, BasicStoredDomainEvent $notification);
}
