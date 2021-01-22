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
}

/**
 * @implements Service<MyRequest, void>
 */
class MyReturnVoidService implements Service
{
    /** @var int */
    private $internalCounter = 0;

    /**
     * @param MyRequest $request
     */
    public function execute($request)
    {
        ++$this->internalCounter;
    }

    public function getCounter(): int
    {
        return $this->internalCounter;
    }
}

class MyRequest
{
    public function getData(): array
    {
        return ['foo' => 'bar'];
    }
}
