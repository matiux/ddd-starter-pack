<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Domain\Service;

use DDDStarterPack\Domain\Service\NoRequestService;
use PHPUnit\Framework\TestCase;

class NoRequestServiceTest extends TestCase
{
    /**
     * @test
     */
    public function use_service(): void
    {
        $service = new MyNoRequestService();

        // Psalm gets angry - as it should be
        //$service->execute(new \stdClass());
        //$service->execute('Foo');

        $result = $service->execute();

        self::assertNotEmpty($result);
        self::assertArrayHasKey('foo', $result);
    }
}

/**
 * @implements NoRequestService<array>
 */
class MyNoRequestService implements NoRequestService
{
    public function execute($request = null): array
    {
        return ['foo' => 'bar'];
    }
}
