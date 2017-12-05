<?php

namespace DDDStarterPack\Infrastructure\Domain\Model\Message\Doctrine;

use DDDStarterPack\Domain\Model\Message\BasicPublishedMessage;
use DDDStarterPack\Domain\Model\Message\PublishedMessageTracker;
use Doctrine\ORM\EntityRepository;
use DDDStarterPack\Domain\Model\Event\StoredDomainEvent;

class BasicDoctrinePublishedMessageTracker extends EntityRepository implements PublishedMessageTracker
{
    /**
     * Ritorna l'ID dell'ultimo PublishedMessage
     * Questo repository contine un solo record per exchangeName, che rappresenta l'ultimo evento pubblicato
     *
     * @param string $exchangeName
     * @return int|null
     */
    public function mostRecentPublishedMessageId(string $exchangeName): ?int
    {
        /** @var $messageTracked BasicPublishedMessage */
        $messageTracked = $this->findOneByExchangeName($exchangeName);

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
        if (!$notification) {

            return null;
        }

        $maxId = $notification->eventId();

        $publishedMessage = $this->findOneByExchangeName($exchangeName);

        if (null === $publishedMessage) {

            $publishedMessage = new BasicPublishedMessage(
                $exchangeName,
                $maxId
            );
        }

        $publishedMessage->updateMostRecentPublishedMessageId($maxId);

        $this->getEntityManager()->persist($publishedMessage);
    }
}
