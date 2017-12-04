<?php

namespace Tests\DDDStarterPack\Fake\Domain\Model\Event;

use DDDStarterPack\Domain\Model\Event\BasicStoredDomainEvent;
use DDDStarterPack\Domain\Model\Event\StoredDomainEvent;

class StoredEvent extends BasicStoredDomainEvent implements StoredDomainEvent
{
}
