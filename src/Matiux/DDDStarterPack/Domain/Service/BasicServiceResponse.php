<?php

namespace DDDStarterPack\Domain\Service;

abstract class BasicServiceResponse implements ServiceResponse
{
    public const ERROR_CODE = 1;
    public const SUCCESS_CODE = 0;

    private $success;
    private $message = '';
    private $code;

    private function __construct()
    {
    }

    public static function error(string $message = ''): self
    {
        $response = new static();
        $response->code = $response->errorCode();
        $response->success = false;
        $response->message = trim($message);

        return $response;
    }

    abstract protected function errorCode(): int;

    public static function success(string $message = ''): self
    {
        $response = new static();
        $response->code = $response->successCode();
        $response->success = true;
        $response->message = trim($message);

        return $response;
    }

    abstract protected function successCode(): int;

    public static function create(): self
    {
        return new static();
    }

    public function withMessage(string $message): self
    {
        $this->message = trim($message);

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

    public function message()
    {
        return $this->message;
    }

    public function code(): int
    {
        return $this->code;
    }
}
