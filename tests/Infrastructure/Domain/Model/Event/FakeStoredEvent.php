<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\BasicStoredDomainEvent;
use DDDStarterPack\Domain\Model\Event\StoredDomainEventInterface;

class FakeStoredEvent extends BasicStoredDomainEvent implements StoredDomainEventInterface
{
}
