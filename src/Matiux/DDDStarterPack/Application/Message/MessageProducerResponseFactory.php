<?php

namespace DDDStarterPack\Application\Message;

interface MessageProducerResponseFactory
{
    public function build(int $sentMessages, $originalResponse = null): MessageProducerResponse;
}
