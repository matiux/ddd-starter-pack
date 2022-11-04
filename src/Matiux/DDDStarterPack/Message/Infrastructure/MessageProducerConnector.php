<?php

declare(strict_types=1);

namespace DDDStarterPack\Message\Infrastructure;

use DDDStarterPack\Message\Infrastructure\Configuration\Configuration;

/**
 * @template T
 *
 * @extends MessageProducer<T>
 */
interface MessageProducerConnector extends MessageProducer
{
    public function name(): string;

    public function bootstrap(Configuration $configuration): void;
}
