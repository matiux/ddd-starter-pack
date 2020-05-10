<?php

namespace DDDStarterPack\Domain\Service;

abstract class BasicServiceResponse implements ServiceResponse
{
    public const ERROR_CODE = 1;
    public const SUCCESS_CODE = 0;

    private $success;
    private $body = null;
    private $code;

    private function __construct()
    {
    }

    public static function error($body = null): self
    {
        $response = new static();
        $response->code = $response->errorCode();
        $response->success = false;
        $response->body = $body;

        return $response;
    }

    public static function success($body = null): self
    {
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

    public function withBody($body): self
    {
        $this->body = $body;

        return $this;
    }

    public function withCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function body()
    {
        return $this->body;
    }

    public function code(): int
    {
        return $this->code;
    }
}
