<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Service\Domain;

use DDDStarterPack\Service\Domain\Service;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    /**
     * @test
     */
    public function use_service(): void
    {
        $service = new MyService();

        // Psalm gets angry - as it should be
        // $service->execute(new \stdClass());

        $data = $service->execute(new MyRequest());

        self::assertSame(['foo' => 'bar'], $data);
    }
}

/**
 * @implements Service<MyRequest, array>
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

    /**
     * @param MyRequest $request
     *
     * @return array
     */
    public function __invoke($request): array
    {
        return $this->execute($request);
    }
}

class MyRequest
{
    public function getData(): array
    {
        return ['foo' => 'bar'];
    }
}
