<?php

declare(strict_types=1);

namespace DDDStarterPack\Message;

use DDDStarterPack\Message\Configuration\Configuration;

interface MessageConsumerConnector extends MessageConsumer
{
    public function name(): string;

    public function bootstrap(Configuration $configuration): void;
}
