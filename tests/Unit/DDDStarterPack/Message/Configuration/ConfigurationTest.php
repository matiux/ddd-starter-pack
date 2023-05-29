<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Message\Configuration;

use DDDStarterPack\Message\Configuration\Configuration;
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
