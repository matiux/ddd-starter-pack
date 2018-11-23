<?php

namespace DDDStarterPack\Infrastructure\Domain\Model\Doctrine\Event;

use DDDStarterPack\Domain\Model\Event\DomainEvent;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventFactory;
use DDDStarterPack\Domain\Service\Serializer;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerBuilder;

abstract class BasicDoctrineEventStore extends EntityRepository
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var StoredDomainEventFactory
     */
    protected $storedDomainEventFactory;

    public function allStoredEventsSince(?int $anEventId, ?int $limit = null): \ArrayObject
    {
        $qb = $this->createQueryBuilder('e');

        /**
         * Se $anEventId == null, allora il WHERE viene tralasciato e non messo nella query
         */
        if ($anEventId) {
            $qb->where('e.eventId > :eventId');
            $qb->setParameter('eventId', $anEventId);
        }

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        $qb->orderBy('e.eventId');

        //$sql = $query->getQuery()->getSQL();
        $query = $qb->getQuery();
        $results = $query->getResult();

        return new \ArrayObject($results);
    }

    private function serializer()
    {
        if (null === $this->serializer) {

            $this->serializer = SerializerBuilder::create()
                ->build();
        }

        return $this->serializer;
    }

    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    public function setStoredDomainEventFactory(StoredDomainEventFactory $storedDomainEventFactory)
    {
        $this->storedDomainEventFactory = $storedDomainEventFactory;
    }

    public function nextId(): ?int
    {
        return null;
    }

    public function add(DomainEvent $domainEvent)
    {
        $serializedEvent = $this->serializer()->serialize($domainEvent, 'json');

        $storedEvent = $this->storedDomainEventFactory->build(
            $this->nextId(),
            get_class($domainEvent),
            $domainEvent->occurredOn(),
            $serializedEvent
        );

        $this->getEntityManager()->persist($storedEvent);
    }

    public function addBulk(\ArrayObject $bulkEvents)
    {
        foreach ($bulkEvents as $domainEvent) {

            if ($domainEvent instanceof DomainEvent) {

                $this->add($domainEvent);
            }
        }

        $this->getEntityManager()->flush();
    }
}
