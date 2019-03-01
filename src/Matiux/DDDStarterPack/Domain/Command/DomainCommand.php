<?php

namespace DDDStarterPack\Domain\Command;

use DateTimeImmutable;

interface DomainCommand
{
    public function occurredAt(): DateTimeImmutable;
}
