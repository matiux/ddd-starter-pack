<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Application\Message\Configuration\Configuration;

interface MessageConsumerConnector extends MessageConsumer
{
    public function name(): string;

    public function bootstrap(Configuration $configuration): void;
}
