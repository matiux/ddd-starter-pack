<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Application\Message\Configuration;

use DDDStarterPack\Application\Message\Configuration\Configuration;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function create_message_configuration(): void
    {
        $configuration = Configuration::withParams('SQS', ['foo' => 'bar']);

        self::assertSame('SQS', $configuration->getDriverName());

        $params = ['foo' => 'bar'];
        self::assertSame($params, $configuration->getParams());
    }
}
