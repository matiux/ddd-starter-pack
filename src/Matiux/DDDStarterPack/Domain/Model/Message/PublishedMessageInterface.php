<?php

namespace DDDStarterPack\Domain\Model\Message;

interface PublishedMessageInterface
{
    public function mostRecentPublishedMessageId(): int;

    public function updateMostRecentPublishedMessageId($maxId);

    public function trackerId(): int;

    public function exchangeName(): string;
}
