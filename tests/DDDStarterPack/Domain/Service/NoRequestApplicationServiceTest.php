<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Domain\Service;

use DDDStarterPack\Domain\Service\NoRequestApplicationService;
use PHPUnit\Framework\TestCase;

class NoRequestApplicationServiceTest extends TestCase
{
    /**
     * @test
     */
    public function use_service(): void
    {
        $service = new MyNoRequestApplicationService();

        // Psalm gets angry - as it should be
        //$service->execute(new \stdClass());
        //$service->execute('Foo');

        $result = $service->execute();

        self::assertNotEmpty($result);
        self::assertArrayHasKey('foo', $result);
    }
}

class MyNoRequestApplicationService implements NoRequestApplicationService
{
    public function execute($request = null): array
    {
        return ['foo' => 'bar'];
    }
}
