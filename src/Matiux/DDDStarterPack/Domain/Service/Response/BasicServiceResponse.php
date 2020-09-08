<?php

declare(strict_types=1);

namespace DDDStarterPack\Domain\Service\Response;

/**
 * @template B of mixed
 * @implements ServiceResponse<B>
 * @psalm-suppress PropertyNotSetInConstructor
 */
abstract class BasicServiceResponse implements ServiceResponse
{
    public const ERROR_CODE = 1;
    public const SUCCESS_CODE = 0;

    /** @var bool */
    private $success;

    /** @var B */
    private $body;

    /** @var int */
    private $code;

    final private function __construct()
    {
    }

    /**
     * @template T of mixed
     *
     * @param T $body
     *
     * @return static<T>
     */
    public static function error($body = null)
    {
        /** @var static<T> */
        $response = new static();
        $response->code = $response->errorCode();
        $response->success = false;
        $response->body = $body;

        return $response;
    }

    /**
     * @template T of mixed
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

    abstract protected function errorCode(): int;

    abstract protected function successCode(): int;

    public static function create(): self
    {
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
}
