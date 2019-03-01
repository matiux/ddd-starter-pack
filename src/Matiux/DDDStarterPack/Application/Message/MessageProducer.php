<?php

namespace DDDStarterPack\Application\Message;

use ArrayObject;

interface MessageProducer extends MessageService
{
    public function send(Message $message): MessageProducerResponse;

    public function sendBatch(ArrayObject $messages): MessageProducerResponse;

    public function getBatchLimit(): int;
}
