<?php

declare(strict_types=1);

namespace DDDStarterPack\Application\Message;

use DDDStarterPack\Application\Message\Configuration\Configuration;

/**
 * @template T
 * @extends MessageProducer<T>
 */
interface MessageProducerConnector extends MessageProducer
{
    public function name(): string;

    public function bootstrap(Configuration $configuration): void;
}
