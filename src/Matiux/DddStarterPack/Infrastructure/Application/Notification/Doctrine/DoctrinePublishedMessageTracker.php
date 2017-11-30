<?php

namespace DddStarterPack\Infrastructure\Application\Notification\Doctrine;

use DddStarterPack\Domain\Model\Message\PublishedMessage;
use DddStarterPack\Domain\Model\Message\PublishedMessageTracker;
use Doctrine\ORM\EntityRepository;
use DddStarterPack\Domain\Model\Event\StoredEvent;

class DoctrinePublishedMessageTracker extends EntityRepository implements PublishedMessageTracker
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
        /** @var $messageTracked PublishedMessage */
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
     * @param StoredEvent $notification
     * @return null
     */
    public function trackMostRecentPublishedMessage(string $exchangeName, StoredEvent $notification)
    {
        if (!$notification) {

            return null;
        }

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
