<?php

declare(strict_types=1);

namespace Tests\DDDStarterPack\Domain\Service;

use DDDStarterPack\Domain\Service\Response\BasicServiceResponse;
use PHPUnit\Framework\TestCase;

class BasicServiceResponseTest extends TestCase
{
    /**
     * @test
     */
    public function create_error_response(): void
    {
        $response = ServiceResponse::error('Error message');

        self::assertSame('Error message', $response->body());
        self::assertFalse($response->isSuccess());
        self::assertSame(1, $response->code());
    }

    /**
     * @test
     */
    public function create_success_response(): void
    {
        $response = ServiceResponse::success(['status' => 'Success message']);

        self::assertSame(['status' => 'Success message'], $response->body());
        self::assertTrue($response->isSuccess());
        self::assertSame(0, $response->code());
    }
}

/**
 * Class ServiceResponse.
 *
 * @template T of mixed
 * @extends BasicServiceResponse<T>
 */
class ServiceResponse extends BasicServiceResponse
{
    protected function errorCode(): int
    {
        return 1;
    }

    protected function successCode(): int
    {
        return 0;
    }
}
