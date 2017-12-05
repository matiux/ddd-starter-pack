<?php

namespace DDDStarterPack\Infrastructure\Domain\Model\Event\Doctrine;


use DDDStarterPack\Domain\Event\Serializer;
use DDDStarterPack\Domain\Model\Event\DomainEvent;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventFactory;
use Doctrine\ORM\EntityRepository;
use JMS\Serializer\SerializerBuilder;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;

abstract class BasicDoctrineEventStore extends EntityRepository
{
    protected $serializer;

    protected $storedDomainEventFactory;

    public function allStoredEventsSince(?int $anEventId, ?int $limit = null): \ArrayObject
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

        $results = $query->getQuery()->getResult();

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

    public function setSerializer(Serializer $serializer)
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
