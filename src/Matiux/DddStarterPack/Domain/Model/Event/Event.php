<?php

namespace DddStarterPack\Domain\Model\Event;

interface Event
{
    public function occurredOn(): \DateTimeImmutable;
}
