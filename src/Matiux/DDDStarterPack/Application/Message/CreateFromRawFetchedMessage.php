<?php

namespace DDDStarterPack\Application\Message;

use DateTimeImmutable;

abstract class CreateFromRawFetchedMessage
{
    public function execute($rawMessage, DateTimeImmutable $occurredAt = null)
    {
        $this->validateRawMessage($rawMessage);

        return $this->create($rawMessage);
    }

    abstract protected function validateRawMessage($rawMessage);

    abstract protected function create($rawMessage);
}
