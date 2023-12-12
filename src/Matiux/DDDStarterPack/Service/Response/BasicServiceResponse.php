<?php

declare(strict_types=1);

namespace DDDStarterPack\Service\Response;

/**
 * @template B of mixed
 *
 * @implements ServiceResponse<B>
 *
 * @psalm-suppress PropertyNotSetInConstructor, UnsafeGenericInstantiation
 */
abstract class BasicServiceResponse implements ServiceResponse
{
    public const ERROR_CODE = 1;
    public const SUCCESS_CODE = 0;

    private bool $success;

    /** @var B */
    private $body;

    private int $code;

    final protected function __construct() {}

    /**
     * @template T of B | null
     *
     * @param T $body
     *
     * @return static<T>
     */
    public static function error($body = null): self
    {
        /** @var static<T> */
        $response = new static();
        $response->code = $response->errorCode();
        $response->success = false;
        $response->body = $body;

        return $response;
    }

    /**
     * @template T of B | null
     *
     * @param T $body
     *
     * @return static<T>
     */
    public static function success($body = null): self
    {
        /** @var static<T> */
        $response = new static();
        $response->code = $response->successCode();
        $response->success = true;
        $response->body = $body;

        return $response;
    }

    /**
     * @template T of B | null
     *
     * @return static<T>
     */
    public static function create(): self
    {
        /** @var static<T> */
        return new static();
    }

    /**
     * @param B $body
     *
     * @return static
     */
    public function withBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function withCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param bool $status
     *
     * @return $this
     */
    public function withSuccessStatus(bool $status): self
    {
        $this->success = $status;

        return $this;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * @return B
     */
    public function body()
    {
        return $this->body;
    }

    public function code(): int
    {
        return $this->code;
    }

    protected function errorCode(): int
    {
        return (int) static::ERROR_CODE;
    }

    protected function successCode(): int
    {
        return (int) static::SUCCESS_CODE;
    }
}
