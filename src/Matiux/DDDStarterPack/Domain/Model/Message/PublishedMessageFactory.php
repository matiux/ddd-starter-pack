<?php

namespace DDDStarterPack\Domain\Model\Message;

interface PublishedMessageFactory
{
    public function build($trackerId, string $exchangeName, int $aMostRecentPublishedMessageId): PublishedMessageInterface;
}
