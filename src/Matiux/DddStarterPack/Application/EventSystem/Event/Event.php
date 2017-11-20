<?php

namespace DddStarterPack\Application\EventSystem\Event;

interface Event
{
    public function occurredOn(): \DateTimeImmutable;
}
