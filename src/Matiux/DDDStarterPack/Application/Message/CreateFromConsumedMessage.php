<?php

namespace DDDStarterPack\Application\Message;

use DateTimeImmutable;

abstract class CreateFromConsumedMessage
{
    public function execute($consumedMessage, DateTimeImmutable $occurredAt = null)
    {
        $this->validateConsumedMessage($consumedMessage);

        return $this->create($consumedMessage);
    }

    abstract protected function validateConsumedMessage($consumedMessage);

    abstract protected function create($consumedMessage);
}
