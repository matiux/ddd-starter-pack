<?php

namespace Tests\DDDStarterPack\Infrastructure\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\BasicStoredDomainEvent;
use DDDStarterPack\Domain\Model\Event\StoredDomainEvent;

class FakeStoredEvent extends BasicStoredDomainEvent implements StoredDomainEvent
{
}
