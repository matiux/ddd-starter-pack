<?php

namespace DddStarterPack\Infrastructure\Domain\Model\Event\Doctrine;

use DddStarterPack\Domain\Model\Event\EventStore;
use DddStarterPack\Domain\Model\Event\StoredEvent;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerBuilder;

class DoctrineEventStore extends EntityRepository implements EventStore
{
    private $serializer;

    public function append(StoredEvent $storedEvent)
    {
        $this->getEntityManager()->persist($storedEvent);
    }

    public function allStoredEventsSince($anEventId)
    {
        $query = $this->createQueryBuilder('e');

        /**
         * Se $anEventId == null, allora il WHERE viene tralasciato e non messo nella query
         */
        if ($anEventId) {
            $query->where('e.eventId > :eventId');
            $query->setParameter('eventId', $anEventId);
        }

        $query->orderBy('e.eventId');

        //$sql = $query->getQuery()->getSQL();

        return $query->getQuery()->getResult();
    }

    private function serializer()
    {
        if (null === $this->serializer) {

            $this->serializer = SerializerBuilder::create()
                ->build();
        }

        return $this->serializer;
    }

    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function nextId(): int
    {

    }
}
