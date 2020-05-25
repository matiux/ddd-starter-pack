<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Application\Message\Configuration\Configuration;

interface MessageProducerConnector extends MessageProducer
{
    public function name(): string;

    public function bootstrap(Configuration $configuration): void;
}
