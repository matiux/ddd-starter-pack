<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

interface MessageProducerResponseFactory
{
    /**
     * @param int        $sentMessages
     * @param null|mixed $originalResponse
     *
     * @return MessageProducerResponse
     */
    public function build(int $sentMessages, $originalResponse = null): MessageProducerResponse;
}
