<?php

declare(strict_types=1);

namespace Tests\Unit\DDDStarterPack\Service;

use DDDStarterPack\Service\Response\BasicServiceResponse;
use PHPUnit\Framework\TestCase;

class BasicServiceResponseTest extends TestCase
{
    /**
     * @test
     */
    public function create_error_response(): void
    {
        // $response = ServiceResponse::error('Error message'); // Psalm si arrabbia, giustamente
        $response = ServiceResponse::error(['status' => 'Error message']);

        self::assertSame(['status' => 'Error message'], $response->body());
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

    /**
     * @test
     */
    public function create_specific_response(): void
    {
        $response = ServiceResponse::specificResponse();

        self::assertSame(['msg' => 'Specific message'], $response->body());
        self::assertTrue($response->isSuccess());
        self::assertSame(2, $response->code());
    }
}

/**
 * @template T of array<string, string>
 *
 * @extends BasicServiceResponse<T>
 */
class ServiceResponse extends BasicServiceResponse
{
    public static function specificResponse(): self
    {
        $response = new static();
        $response->withCode(2);
        $response->withSuccessStatus(true);
        $response->withBody(['msg' => 'Specific message']);

        return $response;
    }

    protected function errorCode(): int
    {
        return 1;
    }

    protected function successCode(): int
    {
        return 0;
    }
}
