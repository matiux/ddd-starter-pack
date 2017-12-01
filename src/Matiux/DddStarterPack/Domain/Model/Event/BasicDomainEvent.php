<?php

namespace DddStarterPack\Domain\Model\Event;

abstract class BasicDomainEvent
{
    protected $occurredOn;

    public function __construct()
    {
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
