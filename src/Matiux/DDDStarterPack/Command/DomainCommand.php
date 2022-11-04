<?php

declare(strict_types=1);

namespace DDDStarterPack\Command;

interface DomainCommand
{
    public function occurredAt(): \DateTimeImmutable;
}
