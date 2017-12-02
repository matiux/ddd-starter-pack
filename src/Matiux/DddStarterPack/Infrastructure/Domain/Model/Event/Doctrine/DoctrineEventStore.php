<?php

namespace DddStarterPack\Infrastructure\Domain\Model\Event\Doctrine;

use DddStarterPack\Domain\Model\Event\BulkDomainEvent;
use DddStarterPack\Domain\Model\Event\EventStore;
use DddStarterPack\Domain\Model\Event\StoredDomainEvent;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerBuilder;

class DoctrineEventStore extends EntityRepository implements EventStore
{
    private $serializer;

    public function append(StoredDomainEvent $storedEvent)
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

    public function add(StoredDomainEvent $storedEvent)
    {

    }

    public function addBulk(BulkDomainEvent $bulkDomainEvent)
    {
        $batchSize = 20;
        for ($i = 1; $i <= 10000; ++$i) {
            $user = new CmsUser;
            $user->setStatus('user');
            $user->setUsername('user' . $i);
            $user->setName('Mr.Smith-' . $i);
            $em->persist($user);
            if (($i % $batchSize) === 0) {
                $em->flush();
                $em->clear(); // Detaches all objects from Doctrine!
            }
        }
        $em->flush(); //Persist objects that did not make up an entire batch
        $em->clear();
    }
}
