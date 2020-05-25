<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Domain\Service;

use DDDStarterPack\Domain\Service\Service;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @test
     */
    public function user_service(): void
    {
        $service = new MyService();

        // Psalm gets angry - as it should be
        // $service->execute(new \stdClass());

        $data = $service->execute(new MyRequest());

        self::assertSame(['foo' => 'bar'], $data);
    }
}

/**
 * Class MyService.
 *
 * @implements Service<MyRequest>
 */
class MyService implements Service
{
    /**
     * @param MyRequest $request
     *
     * @return array
     */
    public function execute($request): array
    {
        return $request->getData();
    }
}

class MyRequest
{
    public function getData(): array
    {
        return ['foo' => 'bar'];
    }
}
